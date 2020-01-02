<?php
define('NBELEM', !empty($_POST['nbElem']) ? htmlspecialchars($_POST['nbElem']) : NULL);
include_once 'generale.php';
$generique = !empty($_POST['BoutonsGenerique']) ? true : false;

/**
 * @param array $array
 * @param $texte
 * @param $identifiant
 */
function gestion(array $array, $texte, $identifiant)
{
    if (count($array) > 1) {
        echo PHP_EOL . '<h4>' . $texte . '</h4><details open><summary></summary>' . PHP_EOL;
        echo '<div id="' . $identifiant . '">';

        $estUnTermes = strcmp($identifiant, 'termes') == 0;

        if ($estUnTermes)
            echo '<div class="row">' . PHP_EOL;

        for ($i = 0; $i < NBELEM; $i++) {
            echo PHP_EOL . $array[$i] . '<br/>';
        }

        if ($estUnTermes)
            echo '</div>' . PHP_EOL;
        echo PHP_EOL . '</details></div><br/>' . PHP_EOL . PHP_EOL;
    }
}

/**
 * @param $noeud
 * @return mixed
 */
function verifieSiIdDansBDD($noeud)
{
    $existe = requeteId("SELECT terme FROM eid WHERE id = :id", $noeud);

    if (!$existe) {
        $terme = recuperationTermeParIdSurJDM($noeud);
        $terme = substr($terme, 1, (strrpos($terme, '\'') - 1));

        if (strpos($terme, '=') !== false)
            $terme = '<p class="motAnglai">' . str_replace('=', '', $terme) . '</p>';
        $bdd = connexionBDD();

        remplieTableEid($bdd, $noeud, $terme);

        return $terme;
    } else {
        return $existe['terme'];
    }
}

/**
 * @param String $id
 * @return string
 */
function recuperationTermeParIdSurJDM(String $id)
{
    $output = recuperationSurJDM($id, "id", '');
    list($positionDebut, $positionFin) = debutEtFinCode($output);

    if (($positionFin - $positionDebut) > 0) {
        $code = substr($output, $positionDebut, ($positionFin - $positionDebut));
        if (!empty($code)) {
            $text = explode('//', $code);
            $lignTerme = explode(';', $text[3]);
            $nb = count($lignTerme);
            if ($nb == 11) {
                return utf8_encode($lignTerme[10]);
            } else if ($nb == 10) {
                return utf8_encode($lignTerme[7]);
            }
        }
    }
    return $id;
}

/**
 * @param String $idRelation
 * @return mixed|String
 */
function recupereNonRelation(String $idRelation)
{
    $existe = requeteId('SELECT nom FROM typerelation WHERE id = :id', $idRelation);

    return '<p class="relationI">' . $existe['nom'] . '</p>';
}

/**
 * @param String $idRelation
 * @param $requete
 * @return mixed
 */
function requeteId(String $requete, String $idRelation)
{
    $bdd = connexionBDD();

    $reponse = $bdd->prepare($requete);
    $reponse->execute(array('id' => $idRelation));
    $existe = $reponse->fetch();
    $reponse->closeCursor();

    return $existe;
}

/**
 * @param String $noeud1
 * @param String $noeud2
 * @param String $relation
 * @param int $poids
 * @return string
 */
function relationEtPoids(String $noeud1, String $relation, String $noeud2, int $poids): string
{
    $str = verifieSiIdDansBDD($noeud1) . ' ' . recupereNonRelation($relation) . ' ' . verifieSiIdDansBDD($noeud2) . '</b> (';

    return $poids >= 0 ? '<b>' . $str . $poids . ')' : '<b class="alert-warning">' . $str . '<b id="negative">' . $poids . '</b>)';
}

/**
 * @param $array
 * @param $type
 * @param $nbElem
 * @return string
 */
function lesNPremier($array, $type, $nbElem)
{
    /** @var String $lesNPremier */
    $lesNPremier = '';

    if (!empty($array)) {
        if (strcmp($type, "relation") === 0) {
            for ($i = 0; $i < $nbElem; $i++) {
                $arrayRelationPoid = decoupeDonnee($array, $i);
                /** @var array $arrayRelationPoid */
                $lesNPremier .= relationEtPoids($arrayRelationPoid[2], $arrayRelationPoid[3], $arrayRelationPoid[4], $arrayRelationPoid[1]) . finLigne($i, $nbElem);
            }
        } else if (strcmp($type, "noeud") === 0) {
            for ($i = 0; $i < $nbElem; $i++) {
                if (!empty($array[$i])) {
                    $arrayNomPoid = decoupeDonnee($array, $i);
                    $lesNPremier .= NomEtPoids($arrayNomPoid[2], $arrayNomPoid[1]) . (finLigne($i, $nbElem));
                }
            }
        }
    }
    return $lesNPremier;
}

/**
 * @param int $i
 * @param $nbElem
 * @return string
 */
function finLigne(int $i, int $nbElem): string
{
    return ($i != ($nbElem - 1)) ? '//' : '';
}

/**
 * @param $array
 * @param int $i
 * @return array
 */
function decoupeDonnee($array, int $i): array
{
    if (!empty($array[$i]))
        $arrayRelationPoid = explode('::', $array[$i]);
    /** @var array $arrayRelationPoid */
    return $arrayRelationPoid;
}

/**
 * @param $donnes
 * @return array
 */
function separationDonne($donnes)
{
    return explode('//', $donnes);
}

/**
 * @param PDO $bdd
 * @param string $champRecherche
 * @return array|string
 */
function verificationExisteTermeBDD(PDO $bdd, string $champRecherche)
{
    $reponse = $bdd->prepare('SELECT description, nbSauv FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $champRecherche));

    return $reponse->fetch();
}

/**
 * @param $output
 * @return array
 */
function debutEtFinCode($output): array
{
    $positionDebut = strpos($output, '<CODE>') + 6;
    $positionFin = strpos($output, '</CODE>') - 9;

    return array($positionDebut, $positionFin);
}

/**
 * @param string $champRecherche
 */
function lancementDeLaRecherche(string $champRecherche)
{
    $bdd = connexionBDD();

    $existe = verificationExisteTermeBDD($bdd, $champRecherche);

    if ($existe[0] == '') {
        situationTermeNonConnue($champRecherche, $bdd);
    } else {
        if ($existe['nbSauv'] >= NBELEM) {
            situationTermeConnue($bdd, $champRecherche);
        } else {
            $reponse = $bdd->prepare('delete FROM jeuxdemots WHERE terme = :terme');
            $reponse->execute(array('terme' => $champRecherche));

            situationTermeNonConnue($champRecherche, $bdd);
        }
    }
}