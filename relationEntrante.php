<?php
include 'principale.php';

/**
 * @param array $rEntrantes
 */
function gestionRelationEntrante(array $rEntrantes)
{
    gestion($rEntrantes, 'relations Entrantes:', 'orangered');
}

/**
 * @param array $relationE
 */
function afficheRelationE(array $relationE)
{
    echo '<div id="relationEntrante" class="col-xl">';
    gestionRelationEntrante($relationE);
    echo '</div>';
}

// situation Terme Connue dans la BDD
if (!empty($champRecherche)) {
//        if (htmlspecialchars($_POST['rel']))

    $bdd = connexionBDD();

    $reponse = $bdd->prepare('SELECT relationE FROM jeuxdemots WHERE terme = :terme');
    $reponse->execute(array('terme' => $champRecherche));

    while ($donnes = $reponse->fetch()) {
        $relationEntrante = separationDonne($donnes[0]);
    }

    if (!empty($relationEntrante))
        afficheRelationE($relationEntrante);
}