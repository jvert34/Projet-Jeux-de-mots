<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Jeux de mots : jeu d'associations de mots du quotidien</title>
    <meta name="description"
          content="Projet basé sur un jeu d'associations de termes, très sympa et amusant. Mots a collectionner, capturer. Trouver les associations les plus pertinentes.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="Jean Philippe Vert">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
    <script async src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="CSS/personnaliser.css"/>
    <link rel="apple-touch-icon" sizes="57x57" href="Image/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="Image/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="Image/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="Image/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="Image/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="Image/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="Image/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Image/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Image/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="Image/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="Image/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Image/favicon-16x16.png">
    <link rel="manifest" href="JSON/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="Image/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<div class="container">
    <h1 style="text-align: center;">Jeux de mots</h1>
</div>

<br/>
<div class="container">
    <form method="POST" name="rechercheTermes">
        <div class="form-group">
            <label for="champRecherche"> </label>
            <input id="champRecherche" name="champRecherche" placeholder="Terme recherché" type="search" autofocus
                   value="<?php echo !empty($_POST['champRecherche']) ? htmlspecialchars($_POST['champRecherche']) : '' ?>"
                   class="form-control"/>
            <input id="BoutonsSoumission" name="BoutonsSoumission" type="submit" value="Chercher"
                   class="btn btn-primary"/>
            <fieldset>
                <legend> Options</legend>
                <label>
                    Num&eacute;ro de relation
                    <input type="number" id="rel" name="rel" min="0" step="1">

                </label>

                <div class="checkbox">
                    <input id="relationSortante" name="relationSortante" type="checkbox" value="norelout"/>
                    <label for="relationSortante">Pas de relations sortantes</label>
                </div>

                <div class="checkbox">
                    <input id="relationEntrante" name="relationEntrante" type="checkbox" value="norelin"/>
                    <label for="relationEntrante">Pas de relations entrantes</label>
                </div>
                <div class="checkbox">
                    <input id="trieAlphabetique" name="trieAlphabetique" type="checkbox" value="tAlpha"/>
                    <label for="trieAlphabetique">Résultats triés alphabétiquement</label>
                </div>
            </fieldset>
        </div>
    </form>
    <div class="container-fluid" id="resultat">
        <script>
            function Go(terme) {
                document.getElementById("champRecherche").value = terme;
                document.forms["rechercheTermes"].submit();
            }
        </script>
        <?php
        include('traitement.php');
        ?>

        <div id="resultat_Final" class="col-sd-6 col-ld-4 col-xl-3">
            <script>
                function infiniteScroll(terme) {
                    // on initialise ajaxready à true au premier chargement de la fonction
                    $(window).data('ajaxready', true);
                    $(window).data('relationS', true);

                    $('#resultat_Final').append('<div id="loader"><p style="text-align:center"><img src="./Image/ajax-loader.gif" alt="loader ajax"></p></div>');

                    let deviceAgent = navigator.userAgent.toLowerCase();
                    let agentID = deviceAgent.match(/(iphone|ipod|ipad)/);

                    $(window).scroll(function () {
                        // On teste si ajaxready vaut false, auquel cas on stoppe la fonction
                        if (($(window).data('ajaxready') === false) && ($(window).data('relationS') === false))
                            return;

                        function extracted(programme) {
                            if (($(window).scrollTop() + $(window).height()) + 50 >= $(document).height()
                                || agentID && ($(window).scrollTop() + $(window).height()) + 150 > $(document).height()) {
                                // lorsqu'on commence un traitement, on met ajaxready à false
                                $(window).data('ajaxready', false);

                                $.post(programme, {champRecherche: terme}, function (data) {
                                        if (data !== '') {
                                            $('#resultat_Final #loader').before(data);
                                            $('#resultat_Final .hidden').fadeIn(400);
                                            // une fois tous les traitements effectués,
                                            // on remet ajaxready à true
                                            // afin de pouvoir rappeler la fonction
                                            if ($(window).data('relationS') === true) {
                                                $(window).data('relationS', false);
                                                $(window).data('ajaxready', true);
                                            }
                                        }
                                    }
                                );
                            }
                        }

                        // S'endormir 10 millisecondes
                        let currentTime = new Date().getTime();
                        while (currentTime + 10 >= new Date().getTime()) {
                        }

                        if ($(window).data('relationS') === true) {
                            extracted('relationSortante.php');
                        } else {
                            extracted('relationEntrante.php');
                            $('#resultat_Final #loader').fadeOut(400);
                        }
                    });
                }

                let terme = "<?php echo !empty($_POST['champRecherche']) ? htmlspecialchars($_POST['champRecherche']) : '' ?>";

                infiniteScroll(terme);
            </script>
        </div>
    </div>
    <noscript>Votre navigateur ne supporte pas JavaScript !</noscript>
</div>
</body>
</html>