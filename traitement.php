<?php
include 'principale.php';

/**
 * @param array $noeuds_termes
 */
function gestionNoeudsTermes(array $noeuds_termes)
{
    gestion($noeuds_termes, "<br> Termes en relation avec le terme recherché", 'blue');
    // On ajoute une entrée dans la table jeux_video
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
 * @param $arrayNoeud
 * @return array
 */
function recupereNomPoidsEtTrieSurPoidsDecroisant($arrayNoeud): array
{
    $arrayNoeudSimplifier = array();

    foreach ($arrayNoeud as $item) {
        if (!empty($item)) {
            list($noeudFinal, $tailleArrayNoeudFinal) = separeDonneeEtConpteTailleArrays($item);
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
 * @param $item
 * @return array
 */
function separeDonneeEtConpteTailleArrays($item): array
{
    $noeudFinal = explode(';', $item);
    $tailleArrayNoeudFinal = count($noeudFinal);
    return array($noeudFinal, $tailleArrayNoeudFinal);
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
 * @param $arrayRelation
 * @return array
 */
function recupereRelationPoidsEtTrieSurPoidsDecroisant($arrayRelation): array
{
    $arrayNoeudSimplifier = array();

    if (!empty($arrayRelation)) {
        foreach ($arrayRelation as $item) {
            if (!empty($item)) {
                list($noeudFinal, $tailleArrayNoeudFinal) = separeDonneeEtConpteTailleArrays($item);
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
 * @param String $description
 * @param array $noeud
 */
function affiche(String $description, array $noeud)
{
    echo '<span style="color: fuchsia; ">' . 'Terme rechercher<b>' . $description . '</b></span><br>';
//    echo '<span style="color: green; ">' . $var2 . '</span><br/><br/>';

    $boolTrie = !empty($_POST['trieAlphabetique']) ? true : false;
    if ($boolTrie === true) {
        natcasesort($noeud);
    }
    gestionNoeudsTermes($noeud);
//                   echo '<span style="color: red; ">' . $typesRelations . '</span><br/>';
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
        affiche($definition, separationDonne($lesNPremierNoeud));

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
 * @param PDO $bdd
 * @param string $champRecherche
 */
function situationTermeConnue(PDO $bdd, string $champRecherche)
{
//        if (htmlspecialchars($_POST['rel']))

    $reponse = $bdd->prepare('SELECT description, noeud FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $champRecherche));

    $description = '';
    $arrayNoeud = '';

    while ($donnes = $reponse->fetch()) {
        $description = $donnes[0];
        $arrayNoeud = separationDonne($donnes[1]);
    }

    affiche($description, $arrayNoeud);
}

if (!empty($champRecherche)) {
    lancementDeLaRecheche($champRecherche);
}