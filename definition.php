<?php
include 'generale.php';

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
    if (!empty($code)) {
        $definiction = preg_split("/[\n]+/",
            preg_replace('{(<br>(<br>)+)+}', '<br/>', strip_tags(preg_replace('`<script[^>]*>.+?</script>`is', '', $code))));
        $termesD = '';

        foreach ($definiction as $termeD) {
            if (strlen($termeD) != 0) {
                if (strlen($termeD) != 7 && strcmp($termeD, "	&nbsp;") != 0)
                    $termesD .= PHP_EOL . '<br>' . utf8_encode($termeD);
            }
        }

        str_replace("<br>	&nbsp;", "", $termesD);

        remplieDefiniction($terme, $termesD);
    } else {
        $bdd = connexionBDD();
        $req = $bdd->prepare('UPDATE jeuxdemots SET chercheDefinition = :chercheDefinition WHERE terme = :terme');
        $req->execute(array(
            'terme' => $terme,
            'chercheDefinition' => 0
        ));
    }
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
    $definiction = '\'' . $terme . '\' (eid=' . $eid . ') </b></h4></summary>' . $definiction . PHP_EOL;

    echo '<summary><h4> Terme rechercher : <b>' . $definiction;
    $req = $bdd->prepare('UPDATE jeuxdemots SET description = :description, chercheDefinition = :chercheDefinition WHERE terme = :terme');
    $req->execute(array(
        'terme' => $terme,
        'description' => $definiction,
        'chercheDefinition' => 0
    ));
}

/**
 * @param string $terme
 * @return mixed
 */
function existeDefiniction(string $terme)
{
    $bdd = connexionBDD();
    $reponse = $bdd->prepare('SELECT chercheDefinition FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $terme));
    $existe = $reponse->fetch();

    return $existe['chercheDefinition'];
}

if (!empty($champRecherche)) {
    $existe = existeDefiniction($champRecherche);
    if (strcmp($existe, "1") === 0) {
        recuperationDefiniction($champRecherche);
    }
}