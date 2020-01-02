<?php
$champRecherche = !empty($_POST['champRecherche']) ? htmlspecialchars($_POST['champRecherche']) : NULL;

/**
 * @return PDO
 */
function connexionBDD(): PDO
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=jdmybnwe_jeuxdemots;charset=utf8', 'jdmybnwe_root', '$2y$14$BS5PQlDhrEbmNnDx6.UEA.q7ZE3zSg8ehPxXAPpNRhX0vI2ukC4.m');
    } catch (Exception $e) {

        die('Erreur connexion BDD');
    }
    return $bdd;
}

/**
 * @param String $champRecherche
 * @param String $type
 * @param String $relation
 * @return bool|string
 */
function recuperationSurJDM(String $champRecherche, String $type, String $relation)
{
// create curl resource
    $ch = curl_init();

    $champRecherche = mb_convert_encoding($champRecherche, 'latin1');
    // set url
    if (strcmp($type, "id") === 0) {
        $url = 'http://www.jeuxdemots.org/rezo-dump.php?goid=' . $champRecherche . '&relout=norelout&relin=norelin';
    } else if (strcmp($type, "generique") === 0) {
        $url = 'www.jeuxdemots.org/diko.php?gotermrel=' . rawurlencode($champRecherche) . '%';
    } else if (strcmp($type, "def") === 0) {
        $url = 'www.jeuxdemots.org/diko.php?gotermrel=' . rawurlencode($champRecherche);
    } else {
        $url = 'http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher&gotermrel=' . $champRecherche . '&rel=';
        if (htmlspecialchars($_POST['rel']))
            if (strcmp($_POST['rel'], 'Choix Relation') != 0)
                $url .= htmlspecialchars($_POST['rel']);

        if (strcmp($relation, "relout") === 0)
            $url .= '&relin=norelin';
        else if (strcmp($relation, "relin") === 0)
            $url .= '&relout=norelout';
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