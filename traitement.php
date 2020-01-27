<?php
include_once 'principale.php';

/**
 * @param array $noeuds_termes
 */
function gestionNoeudsTermes(array $noeuds_termes)
{
    gestion($noeuds_termes, "Termes en relation avec le terme recherché", 'termes');
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
                    $arrayNoeudSimplifier[] = abs($noeudFinal[4]) . '::' . $noeudFinal[4] . '::' . $noeudFinal[5];
                else {
                    $arrayNoeudSimplifier[] = abs($noeudFinal[4]) . '::' . $noeudFinal[4] . '::' . $noeudFinal[2];
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

    return '<div class="col-sm-6 col-md-4 col-lg-3"><a href="#" onclick="Go(' . $nom . ')"><b>' . $nomSansApostrophe . '</b></a> (' . $poids . ')</div>';
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
                // $noeudFinal[4] != 12 => pour ne pas avoir la relation 12
                if ($tailleArrayNoeudFinal == 6 && $noeudFinal[4] != 12 && termesPasPrieEnCompte($noeudFinal)) {
                    $arrayNoeudSimplifier[] = abs($noeudFinal[5]) . '::' . $noeudFinal[5] . '::' . $noeudFinal[2] . '::' . $noeudFinal[4] . '::' . $noeudFinal[3];
                }
            }
        }
        rsort($arrayNoeudSimplifier, SORT_NUMERIC);
    }

    return $arrayNoeudSimplifier;
}

/**
 * @param $noeudFinal
 * @return bool
 */
function termesPasPrieEnCompte($noeudFinal): bool
{
    $pasPrieEnCompte = [2983124, 239128, 241794, 162763, 163012, 2121517, 2121518, 2121520, 2121522, 2121523, 2121529, 2121530, 2121531, 2121532, 2121533, 2121534, 2121535, 2121536, 2121537, 2121538, 2121539, 2121540, 162749, 191669, 2121541, 2121542, 2121543, 2121544, 2121545, 2121547, 2121548, 2355471, 2121549, 2121550, 433957, 241794, 254877, 223173, 2121551, 2121552, 2121553, 2121554, 2121555, 2121557, 2121558, 2121559, 2121560, 2121562, 2121564, 2121566, 2121568, 2121570];
    foreach ($pasPrieEnCompte as $item) {
        if ($noeudFinal[2] == $item || $noeudFinal[3] == $item)
            return false;
    }
    return true;
}

/**
 * @param PDO $bdd
 * @param string $id
 * @param string $terme
 */
function remplieTableEid(PDO $bdd, string $id, string $terme)
{
    $req = $bdd->prepare('INSERT INTO eid (id, terme) VALUES (:id, :terme)');
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
 * @param int $def
 * @param int $nbElem
 */
function remplieTableJeuxDeMots(PDO $bdd, string $champRecherche, string $definition, string $lesNPremierNoeud, string $lesNPremierRelationEntrantes, string $lesNPremierRelationSortantes, int $def, int $nbElem)
{
    $req = $bdd->prepare('INSERT INTO jeuxdemots (terme, description, noeud, relationE, relationS,chercheDefinition, nbSauv) VALUES (:terme, :description, :noeud, :relationE, :relationS,:chercheDefinition,:nbSauv)');
    $req->execute(array(
        'terme' => $champRecherche,
        'description' => $definition,
        'noeud' => $lesNPremierNoeud,
        'relationE' => $lesNPremierRelationEntrantes,
        'relationS' => $lesNPremierRelationSortantes,
        'chercheDefinition' => $def,
        'nbSauv' => $nbElem
    ));
}

/**
 * @param array $noeud
 */
function affiche(array $noeud)
{
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
    $output = recuperationSurJDM($champRecherche, "terme", "relout");
    list($positionDebut, $positionFin) = debutEtFinCode($output);

    if (($positionFin - $positionDebut) > 0) {
        $code = substr($output, $positionDebut, ($positionFin - $positionDebut));
        $text = explode('//', $code);
        $definition = substr(utf8_encode($text[1]), 19);

        $positionDef = strpos($definition, '<def>') + 5;
        $positionFinDef = strpos($definition, '</def>');

        if (($positionFinDef - $positionDef) > 2) {
            $def = 0;
        } else
            $def = 1;

        $definit = preg_split("/[\n]+/", $definition);
        $definition = substr($definition, strlen($definit[0]));

        if (strcmp($definit[1], '<WARNING>TOOBIG_USE_DUMP<WARNING>') == 0)
            $definition = substr($definition, 35);

        $definition = $definit[0] . '</b></h4></summary>' . $definition;

        afficheDefiniction($definition);
//        $var2 = utf8_encode($text[2]);
        $noeuds_termes = preg_split("/[\n,]+/", substr(utf8_encode($text[3]), 66));
//        $typesRelations = utf8_encode($text[4]);
        $nbElem = !empty($_POST['nbElem']) ? $_POST['nbElem'] : 20;
        $lesNPremierNoeud = lesNPremier(recupereNomPoidsEtTrieSurPoidsDecroisant($noeuds_termes), 'noeud', $nbElem);
        affiche(separationDonne($lesNPremierNoeud));

        $rSortantes = !empty($text[5]) ? preg_split("/[\n,]+/", substr(utf8_encode(htmlspecialchars($text[5])), 52)) : '';
        $lesNPremierRelationSortantes = lesNPremier(recupereRelationPoidsEtTrieSurPoidsDecroisant($rSortantes), 'relation', $nbElem);

        $lesNPremierRelationEntrantes = '';
        $output = recuperationSurJDM($champRecherche, "terme", "relin");
        list($positionDebut, $positionFin) = debutEtFinCode($output);
        if (($positionFin - $positionDebut) > 0) {
            $code = substr($output, $positionDebut, ($positionFin - $positionDebut));
            $text = explode('//', $code);

            $rEntrante = !empty($text[5]) ? preg_split("/[\n,]+/", substr(utf8_encode(htmlspecialchars($text[5])), 52)) : '';
            $lesNPremierRelationEntrantes = lesNPremier(recupereRelationPoidsEtTrieSurPoidsDecroisant($rEntrante), 'relation', $nbElem);
        }

        remplieTableJeuxDeMots($bdd, $champRecherche, $definition, $lesNPremierNoeud, $lesNPremierRelationEntrantes, $lesNPremierRelationSortantes, $def, $nbElem);

        echo '<script> definition(\'' . $champRecherche . '\')</script>';
    } else {
        alerteTermeNonExistant($champRecherche);
    }
}

/**
 * @param string $definition
 */
function afficheDefiniction(string $definition)
{
    echo '<div id="definition" class="definition">' . PHP_EOL . '<details open><summary><h4> Terme rechercher : <b>' . $definition . '</details></div><br>';
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

    afficheDefiniction($description);
    affiche($arrayNoeud);
}

if (!empty($champRecherche)) {
    if ($generique)
        include 'generique.php';
    else
        lancementDeLaRecherche($champRecherche);
}