<?php
const constN = 20;

$champRecherche = !empty($_POST['champRecherche']) ? htmlspecialchars($_POST['champRecherche']) : NULL;

/**
 * @param array $array
 * @param $texte
 * @param $color
 */
function gestion(array $array, $texte, $color)
{
    if (count($array) > 1) {
        echo '<h4>' . $texte . '</h4>';
        echo PHP_EOL . '<span style="color: ' . $color . '; ">';
        foreach ($array as $element) {
            echo PHP_EOL . $element . '<br/>';
        }
        echo PHP_EOL . '</span><br/>' . PHP_EOL . PHP_EOL;
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

        if (preg_match('#[a-zéèàêâùïüëA-Z]+>[0-9]+#', $terme, $matches)) {
            $position = strpos($terme, ">") + 1;
            $specification = substr($terme, $position);
            $debutTerme = substr($terme, 0, $position);
            $terme = $debutTerme . verifieSiIdDansBDD($specification);
        }

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
    $output = recuperationSurJDM($id, "id");
    list($positionDebut, $positionFin) = debutEtFinCode($output);

    if (($positionFin - $positionDebut) > 0) {
        $code = substr($output, $positionDebut, ($positionFin - $positionDebut));

        if (!empty($code)) {
            $def = substr(utf8_encode($code), 24);
            $pFin = strpos($def, '\' (');
            $terme = substr($def, 0, $pFin);

            return $terme;
        }
    }
    return $id;
}

/**
 * @param String $relation
 * @return mixed|String
 */
function recupereNonRelation(String $relation)
{
    $existe = requeteId($relation, 'SELECT nom FROM typerelation WHERE id = :id');

    return '<span style="color: blue; ">' . $existe['nom'] . '</span>';
}

/**
 * @param String $relation
 * @param $requete
 * @return mixed
 */
function requeteId(String $requete, String $relation)
{
    $bdd = connexionBDD();

    $reponse = $bdd->prepare($requete);
    $reponse->execute(array('id' => $relation));
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
    return '<b>' . verifieSiIdDansBDD($noeud1) . ' ' . recupereNonRelation($relation) . ' ' . verifieSiIdDansBDD($noeud2) . '</b> avec un poids de ' . $poids;
}

/**
 * @param $array
 * @param $type
 * @return string
 */
function lesNPremier($array, $type)
{
    /** @var String $lesNPremier */
    $lesNPremier = '';

    if (!empty($array)) {
        if (strcmp($type, "relation") === 0) {
            for ($i = 0; $i < constN; $i++) {
                $arrayRelationPoid = decoupeDonnee($array, $i);
                /** @var array $arrayRelationPoid */
                $lesNPremier .= relationEtPoids($arrayRelationPoid[1], $arrayRelationPoid[2], $arrayRelationPoid[3], $arrayRelationPoid[0]) . finLigne($i);
            }
        } else if (strcmp($type, "noeud") === 0) {
            for ($i = 0; $i < constN; $i++) {
                if (!empty($array[$i])) {
                    $arrayNomPoid = decoupeDonnee($array, $i);
                    $lesNPremier .= NomEtPoids($arrayNomPoid[1], $arrayNomPoid[0]) . (finLigne($i));
                }
            }
        }
    }
    return $lesNPremier;
}

/**
 * @param int $i
 * @return string
 */
function finLigne(int $i): string
{
    return ($i != (constN - 1)) ? '//' : '';
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
 * @param string $definition
 * @return false|string
 */
function recupereId(string $definition)
{
    $positionDebut = strpos($definition, '(') + 5;
    $positionFin = strpos($definition, ')');
    $id = substr($definition, $positionDebut, ($positionFin - $positionDebut));

    return $id;
}

/**
 * @param $donnes
 * @return array
 */
function separationDonne($donnes)
{
    $var = explode('//', $donnes);
    return $var;
}

/**
 * @param PDO $bdd
 * @param string $champRecherche
 * @return array|string
 */
function verificationExisteTermeBDD(PDO $bdd, string $champRecherche)
{
    $reponse = $bdd->prepare('SELECT description FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $champRecherche));
    $existe = $reponse->fetch();

    return $existe['description'];
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
 * @return PDO
 */
function connexionBDD(): PDO
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=jeuxrbpx_jeuxdemots;charset=utf8', 'jeuxrbpx_root', '$2y$14$BS5PQlDhrEbmNnDx6.UEA.q7ZE3zSg8ehPxXAPpNRhX0vI2ukC4.m');
    } catch (Exception $e) {

        die('Erreur connexion BDD');
    }
    return $bdd;
}

/**
 * @param string $champRecherche
 */
function lancementDeLaRecheche(string $champRecherche)
{
    $bdd = connexionBDD();

    $existe = verificationExisteTermeBDD($bdd, $champRecherche);

    if ($existe[0] == '') {
        situationTermeNonConnue($champRecherche, $bdd);
    } else {
        situationTermeConnue($bdd, $champRecherche);
    }
}