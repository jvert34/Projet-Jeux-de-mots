<?php
include 'principale.php';

/**
 * @param array $rSortantes
 */
function gestionRelationSortante(array $rSortantes)
{
    gestion($rSortantes, 'relations Sortante', 'relationS');
}

/**
 * @param array $relationS
 */
function afficheRelationS(array $relationS)
{
    echo '<div id="relationSortante" class="col-xl">';
    gestionRelationSortante($relationS);
    echo ' </div>';
}

// situation Terme Connue dans la BDD
if (!empty($champRecherche)) {
//        if (htmlspecialchars($_POST['rel']))

    $bdd = connexionBDD();

    $reponse = $bdd->prepare('SELECT relationS FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $champRecherche));

    while ($donnes = $reponse->fetch()) {
        $relationSortante = separationDonne($donnes[0]);
    }

    if (!empty($relationSortante))
        afficheRelationS($relationSortante);
}