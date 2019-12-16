<?php
include_once 'principale.php';

/**
 * @param String $terme
 */
function recuperationDefiniction(String $terme)
{
    $output = recuperationSurJDM($terme, "def", '');
    list($positionDebut, $positionFin) = debutEtFinDefiniction($output);

    if (($positionFin - $positionDebut) > 0) {
        $code = substr($output, $positionDebut, ($positionFin - $positionDebut));
    }
    $definiction = !empty($code) ? preg_split("/[\n]+/", strip_tags($code)) : '';
    $termesD = '';

    foreach ($definiction as $termeD) {
        if (strlen($termeD) != 0) {
            if (strlen($termeD) != 7 && strcmp($termeD, "	&nbsp;") != 0)
                $termesD .= PHP_EOL . '<br>' . utf8_encode($termeD);
        }
    }

    remplieDefiniction($terme, $termesD);
}

/**
 * @param $output
 * @return array
 */
function debutEtFinDefiniction($output): array
{
    $positionDebut = strpos($output, /** @lang JDM */ '<div style="display:block;margin-bottom:3px;font-size:9pt;border:lightgrey 0px solid;">') + 87;
    $positionFin = strpos($output, '</table></div></div>');

    return array($positionDebut, $positionFin);
}

/**
 * @param string $terme
 * @param string $definiction
 */
function remplieDefiniction(string $terme, string $definiction)
{
    $bdd = connexionBDD();

    $reponse = $bdd->prepare('SELECT id from eid where terme = :terme');
    $reponse->execute(array(
        'terme' => $terme
    ));
    $existe = $reponse->fetch();

    $eid = $existe['id'];
    $definiction = '\'' . $terme . '\' (eid=' . $eid . ') </b></h4>' . $definiction . PHP_EOL;
    str_replace("<br>	&nbsp;", "", $definiction);

    $req = $bdd->prepare('UPDATE jeuxdemots SET description = :description WHERE terme = :terme');
    $req->execute(array(
        'terme' => $terme,
        'description' => $definiction));
}

/**
 * @param string $terme
 * @return mixed
 */
function existeDefiniction(string $terme)
{
    $bdd = connexionBDD();
    $reponse = $bdd->prepare('SELECT description FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $terme));
    $existe = $reponse->fetch();

    return $existe['description'];
}

if (!empty($champRecherche)) {
    $existe = existeDefiniction($champRecherche);
    if (empty($existe)) {
        echo $existe;
    } else {
        recuperationDefiniction($champRecherche);
    }
}