<?php
$champRecherche = !empty($_POST['champRecherche']) ? htmlspecialchars($_POST['champRecherche']) : NULL;
///**
// * @param String $id
// * @return string
// */
//function recuperationTermeGenerique(String $termes)
//{
$output = recuperationSurJDM($champRecherche, "generique", '');
list($positionDebut, $positionFin) = debutEtFinGenerique($output);

if (($positionFin - $positionDebut) > 0) {
    $code = substr($output, $positionDebut, ($positionFin - $positionDebut));
}
$generique = !empty($code) ? preg_split("/[\n,]+/", strip_tags($code)) : '';
$termesG = '';

foreach ($generique as $termeG) {
    if (strlen($termeG) != 0) {
        $termesG .= PHP_EOL . '<article><a href="#" onclick="Go(' . $termeG . ')"><b>' . $termeG . '</b></a></article>';
    }
}

print_r($termesG);
remplieTableTermesGenerique($champRecherche, $termesG);
//}

/**
 * @param $output
 * @return array
 */
function debutEtFinGenerique($output): array
{
    $positionDebut = strpos($output, '<div class="listing">') + 21;
    $positionFin = strpos($output, '</article></div>');

    return array($positionDebut, $positionFin);
}

/**
 * @param string $listeTermes
 * @param string $terme
 */
function remplieTableTermesGenerique(string $terme, string $listeTermes)
{
    $bdd = connexionBDD();
    $req = $bdd->prepare('INSERT INTO termesGenerique (terme, listeDesTermesGenerique) VALUES(:terme, :listeDesTermesGenerique)');
    $req->execute(array(
        'terme' => $terme,
        'listeDesTermesGenerique' => $listeTermes
    ));
}