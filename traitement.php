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
 * @param array $noeuds_termes
 */
function gestionNoeudsTermes(array $noeuds_termes)
{
    gestion($noeuds_termes, "<br> Termes en relation avec le terme recherché", 'blue');
    // On ajoute une entrée dans la table jeux_video
}

/**
 * @param array $rEntrantes
 */
function gestionEntrante(array $rEntrantes)
{
    gestion($rEntrantes, 'relations Entrantes:', 'orangered');
}

/**
 * @param array $rSortantes
 */
function gestionSortante(array $rSortantes)
{
    gestion($rSortantes, 'relations Sortante', 'turquoise');
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
                if (!empty($array[$i]))
                    $arrayRelationPoid = explode('::', $array[$i]);
                /** @var array $arrayRelationPoid */
                $lesNPremier .= relationEtPoids($arrayRelationPoid[1], $arrayRelationPoid[2], $arrayRelationPoid[3], $arrayRelationPoid[0]) . (($i != (constN - 1)) ? '//' : '');
            }
        } else if (strcmp($type, "noeud") === 0) {
            for ($i = 0; $i < constN; $i++) {
                if (!empty($array[$i])) {
                    $arrayNomPoid = explode('::', $array[$i]);
                    $lesNPremier .= NomEtPoids($arrayNomPoid[1], $arrayNomPoid[0]) . (($i != (constN - 1)) ? '//' : '');
                }
            }
        }
    }
    return $lesNPremier;
}

/**
 * @param String $champRecherche
 * @param String $type
 * @return bool|string
 */
function recuperationSurJDM(String $champRecherche, String $type)
{
// create curl resource
    $ch = curl_init();

    // set url
    if (strcmp($type, "id") === 0)
        $url = 'http://www.jeuxdemots.org/rezo-dump.php?goid=' . $champRecherche . '&relout=norelout&relin=norelin';
    else {
        $url = 'http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher&gotermrel=' . $champRecherche . '&rel=';
        if (htmlspecialchars($_POST['rel']))
            $url .= htmlspecialchars($_POST['rel']);

        $relationSortante = !empty($_POST['relationSortante']) ? htmlspecialchars($_POST['relationSortante']) : NULL;
        if ($relationSortante)
            $url .= '&relout=' . $relationSortante;

        $relationEntrante = !empty($_POST['relationEntrante']) ? htmlspecialchars($_POST['relationEntrante']) : NULL;
        if ($relationEntrante)
            $url .= '&relin=' . $relationEntrante;
    }
    curl_setopt($ch, CURLOPT_URL, $url);

    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);
    // close curl resource to free up system resources
    curl_close($ch);
    return $output;
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
        $text = explode('//', $code);
        if (!empty($text[1])) {
            $def = substr(utf8_encode($text[1]), 21);
            $pFin = strpos($def, '\' (');
            $terme = substr($def, 0, $pFin);
            return $terme;
        }
    }
    return $id;
}

/**
 * @param $description
 * @param array $noeud
 * @param array $relationS
 * @param array $relationE
 */
function affiche(String $description, array $noeud, array $relationS, array $relationE)
{
    echo '<span style="color: fuchsia; ">' . 'Terme rechercher<b>' . $description . '</b></span><br>';
//    echo '<span style="color: green; ">' . $var2 . '</span><br/><br/>';

    $boolTrie = !empty($_POST['trieAlphabetique']) ? true : false;
    if ($boolTrie === true) {
        natcasesort($noeud);
    }
    gestionNoeudsTermes($noeud);
//                   echo '<span style="color: red; ">' . $typesRelations . '</span><br/>';
    gestionSortante($relationS);
    gestionEntrante($relationE);
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
 * @param $arrayNoeud
 * @return array
 */
function recupereNomPoidsEtTrieSurPoidsDecroisant($arrayNoeud): array
{
    $arrayNoeudSimplifier = array();

    foreach ($arrayNoeud as $item) {
        if (!empty($item)) {
            $noeudFinal = explode(';', $item);
            $tailleArrayNoeudFinal = count($noeudFinal);
            if ($tailleArrayNoeudFinal >= 5) {
                if ($tailleArrayNoeudFinal == 6)
                    $arrayNoeudSimplifier[] = $noeudFinal[4] . '::' . $noeudFinal[5];
                else {
                    $arrayNoeudSimplifier[] = $noeudFinal[4] . '::' . $noeudFinal[2];
                }
            }
        }
    }
    rsort($arrayNoeudSimplifier, SORT_NUMERIC);

    return $arrayNoeudSimplifier;
}

/**
 * @param String $nom
 * @param int $poids
 * @return string
 */
function NomEtPoids(String $nom, int $poids): string
{
    $nomSansApostrophe = substr($nom, 1, (strlen($nom) - 2));

    return '<a href="#" onclick="Go(' . $nom . ')"><b>' . $nomSansApostrophe . '</b></a> avec un poids de ' . $poids;
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
 * @param $noeud
 * @return mixed
 */
function verifieSiIdDansBDD($noeud)
{
    $bdd = connexionBDD();
    $reponse = $bdd->prepare('SELECT terme FROM eid WHERE id = :id');
    $reponse->execute(array('id' => $noeud));
    $existe = $reponse->fetch();
    $reponse->closeCursor();

    if (!$existe) {
        $terme = recuperationTermeParIdSurJDM($noeud);

        if (preg_match('#[a-zéèàêâùïüëA-Z]+>[0-9]+#', $terme, $matches)) {
            $position = strpos($terme, ">") + 1;
            $specification = substr($terme, $position);
            $debutTerme = substr($terme, 0, $position);
            $terme = $debutTerme . verifieSiIdDansBDD($specification);
        }

        remplieTableEid($bdd, $noeud, $terme);
        return $terme;
    } else {
        return $existe['terme'];
    }
}

/**
 * @param String $relation
 * @return mixed|String
 */
function recupereNonRelation(String $relation)
{
    $bdd = connexionBDD();

    $reponse = $bdd->prepare('SELECT nom FROM typerelation WHERE id = :id');
    $reponse->execute(array('id' => $relation));
    $existe = $reponse->fetch();
    $reponse->closeCursor();

    return '<span style="color: blue; ">' . $existe['nom'] . '</span>';
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
 * @param $arrayRelation
 * @return array
 */
function recupereRelationPoidsEtTrieSurPoidsDecroisant($arrayRelation): array
{
    $arrayNoeudSimplifier = array();

    if (!empty($arrayRelation)) {
        foreach ($arrayRelation as $item) {
            if (!empty($item)) {
                $noeudFinal = explode(';', $item);
                $tailleArrayNoeudFinal = count($noeudFinal);
                if ($tailleArrayNoeudFinal == 6) {
                    $arrayNoeudSimplifier[] = $noeudFinal[5] . '::' . $noeudFinal[2] . '::' . $noeudFinal[4] . '::' . $noeudFinal[3];
                }
            }
        }
        rsort($arrayNoeudSimplifier, SORT_NUMERIC);
    }

    return $arrayNoeudSimplifier;
}

/**
 * @param PDO $bdd
 * @param string $champRecherche
 * @return array|string
 */
function verificationExisteTermeBDD(PDO $bdd, string $champRecherche)
{
    $reponse = $bdd->prepare('SELECT * FROM jeuxrbpx_jeuxdemots.jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $champRecherche));
    $existe = $reponse->fetch();
    return $existe[1];
}

/**
 * @param PDO $bdd
 * @param string $id
 * @param string $terme
 */
function remplieTableEid(PDO $bdd, string $id, string $terme)
{
    $req = $bdd->prepare('INSERT INTO eid (id, terme) VALUES(:id, :terme)');
    $req->execute(array(
        'id' => $id,
        'terme' => $terme,
    ));
}

/**
 * @param PDO $bdd
 * @param string $champRecherche
 * @param string $definition
 * @param string $lesNPremierNoeud
 * @param string $lesNPremierRelationEntrantes
 * @param string $lesNPremierRelationSortantes
 */
function remplieTableJeuxDeMots(PDO $bdd, string $champRecherche, string $definition, string $lesNPremierNoeud, string $lesNPremierRelationEntrantes, string $lesNPremierRelationSortantes)
{
    $req = $bdd->prepare('INSERT INTO jeuxdemots (terme, description, noeud, relationE, relationS) VALUES(:terme, :description, :noeud, :relationE, :relationS)');
    $req->execute(array(
        'terme' => $champRecherche,
        'description' => $definition,
        'noeud' => $lesNPremierNoeud,
        'relationE' => $lesNPremierRelationEntrantes,
        'relationS' => $lesNPremierRelationSortantes
    ));
}

/**
 * @param string $champRecherche
 * @param PDO $bdd
 */
function situationTermeNonConnue(string $champRecherche, PDO $bdd)
{
    $output = recuperationSurJDM(mb_convert_encoding($champRecherche, 'latin1'), "terme");
    list($positionDebut, $positionFin) = debutEtFinCode($output);
    if (($positionFin - $positionDebut) > 0) {
        $code = substr($output, $positionDebut, ($positionFin - $positionDebut));
        $text = explode('//', $code);
        $definition = substr(utf8_encode($text[1]), 19);
        $id = recupereId($definition);
//        $var2 = utf8_encode($text[2]);
        $noeuds_termes = preg_split("/[\n,]+/", substr(utf8_encode($text[3]), 66));
//        $typesRelations = utf8_encode($text[4]);
        $rSortantes = preg_split("/[\n,]+/", substr(utf8_encode(htmlspecialchars($text[5])), 51));
        $rEntrantes = !empty($text[6]) ? preg_split("/[\n,]+/", substr(utf8_encode(htmlspecialchars($text[6])), 51)) : '';
        $lesNPremierNoeud = lesNPremier(recupereNomPoidsEtTrieSurPoidsDecroisant($noeuds_termes), 'noeud');
        $lesNPremierRelationEntrantes = lesNPremier(recupereRelationPoidsEtTrieSurPoidsDecroisant($rEntrantes), 'relation');
        $lesNPremierRelationSortantes = lesNPremier(recupereRelationPoidsEtTrieSurPoidsDecroisant($rSortantes), 'relation');
        affiche($definition, separationDonne($lesNPremierNoeud), separationDonne($lesNPremierRelationEntrantes), separationDonne($lesNPremierRelationSortantes));

        remplieTableJeuxDeMots($bdd, $champRecherche, $definition, $lesNPremierNoeud, $lesNPremierRelationEntrantes, $lesNPremierRelationSortantes);
        remplieTableEid($bdd, $id, $champRecherche);
    } else {
        alerteTermeNonExistant($champRecherche);
    }
}

/**
 * @param string $champRecherche
 */
function alerteTermeNonExistant(string $champRecherche)
{
    echo '<div class="alert alert-warning">';
    echo '&#9888; le terme <b>' . $champRecherche . '</b> n\'existe pas !</div>';
    echo '<script> (function () {alert("\u26A0 le terme n\'existe pas !")})();</script>';
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
 * @param PDO $bdd
 * @param string $champRecherche
 */
function situationTermeConnue(PDO $bdd, string $champRecherche)
{
//        if (htmlspecialchars($_POST['rel']))

    if (!empty($_POST['relationSortante'])) {
        $relationSortante = htmlspecialchars($_POST['relationSortante']);
    } else {
        $relationSortante = NULL;
    }

    if (!empty($_POST['relationEntrante'])) {
        $relationEntrante = htmlspecialchars($_POST['relationEntrante']);
    } else {
        $relationEntrante = NULL;
    }

    $reponse = $bdd->prepare('SELECT description, noeud, relationE, relationS FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $champRecherche));

    $description = '';
    $arrayNoeud = '';
    $relationE = array();
    $relationS = array();

    while ($donnes = $reponse->fetch()) {
        $description = $donnes[0];
        $arrayNoeud = separationDonne($donnes[1]);
        if (!$relationEntrante)
            $relationE = separationDonne($donnes[2]);
        if (!$relationSortante)
            $relationS = separationDonne($donnes[3]);
    }

    affiche($description, $arrayNoeud, $relationS, $relationE);
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

    if ($existe[0] == 0) {
        situationTermeNonConnue($champRecherche, $bdd);
    } else {
        situationTermeConnue($bdd, $champRecherche);
    }
}

if (!empty($champRecherche)) {
    lancementDeLaRecheche($champRecherche);
}