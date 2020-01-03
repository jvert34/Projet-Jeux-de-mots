<?php
include 'generale.php';

$term = $_GET['term'];
$bdd = connexionBDD();

$requete = $bdd->prepare('SELECT * FROM eid WHERE terme LIKE :term'); // j'effectue ma requête SQL grâce au mot-clé LIKE
$requete->execute(array('term' => $term . '%'));

$array = array();

while ($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
{
    array_push($array, $donnee['terme']); // et on ajoute celles-ci à notre tableau
}

echo json_encode($array); // convertion en JSON