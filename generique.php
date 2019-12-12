<?php
$champRecherche = !empty($_POST['champRecherche']) ? htmlspecialchars($_POST['champRecherche']) : NULL;

/**
 * @param String $termes
 */
function recuperationTermeGenerique(String $termes)
{
    $output = recuperationSurJDM($termes, "generique", '');
    list($positionDebut, $positionFin) = debutEtFinGenerique($output);

    if (($positionFin - $positionDebut) > 0) {
        $code = substr($output, $positionDebut, ($positionFin - $positionDebut));
    }
    $generique = !empty($code) ? preg_split("/[\n,]+/", strip_tags($code)) : '';
    $termesG = '';

    foreach ($generique as $termeG) {
        if (strlen($termeG) != 0) {
            $termesG .= PHP_EOL . '<article class="col-md-6 text-center"><a href="#" onclick="Go(' . $termeG . ')"><b>' . $termeG . '</b></a></article>';
        }
    }
    print_r($termesG);
    remplieTableTermesGenerique($termes, $termesG);
}

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
 * @param string $termesGenerique
 * @param string $terme
 */
function remplieTableTermesGenerique(string $terme, string $termesGenerique)
{
    $bdd = connexionBDD();
    $req = $bdd->prepare('INSERT INTO termesgenerique (terme, termesGenerique) VALUES(:terme, :generique)');
    $req->execute(array(
        'terme' => $terme,
        'generique' => $termesGenerique));
}

/**
 * @param string $terme
 * @return mixed
 */
function existeTermesGenerique(string $terme)
{
    $bdd = connexionBDD();
    $reponse = $bdd->prepare('SELECT termesGenerique FROM termesgenerique WHERE terme = :terme');
    $reponse->execute(array('terme' => $terme));
    $existe = $reponse->fetch();

    return $existe['termesGenerique'];
}

$existe = existeTermesGenerique($champRecherche);
if (!empty($existe)) {
    echo $existe;
} else {
    recuperationTermeGenerique($champRecherche);
}