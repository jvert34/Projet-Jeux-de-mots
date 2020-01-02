<?php
include 'generale.php';

ini_set('max_execution_time',3600);
// 1 : on ouvre le fichier
$monfichier = fopen('11112019-LEXICALNET-JEUXDEMOTS-ENTRIES.txt', 'r');

// 2 : on lit la première ligne du fichier
$bdd = connexionBDD();
$req = $bdd->prepare('INSERT INTO eid (id, terme) VALUES(:id, :terme)');

while (!feof($monfichier)) {
    $ligne = utf8_encode(fgets($monfichier));
    $text = explode(';', $ligne);

    if (count($text) == 3 && strcmp($text[0], ' id') != 0) {
        // echo $text[0] . ' => ' . $text[1] . '<br>';
        $req->execute(array(
            'id' => intval($text[0]),
            'terme' => $text[1]
        ));
    }
}
echo 'Fin';

// 3 : quand on a fini de l'utiliser, on ferme le fichier
fclose($monfichier);