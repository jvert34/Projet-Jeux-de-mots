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
    <script async
            src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
    <script async src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.min.css"/>
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

        </div>
        <!--  <script type="text/javascript">
              var _0x38ec = [
                  "\x73\x63\x72\x6F\x6C\x6C",
                  "\x72\x65\x61\x64\x79",
                  "\x68\x65\x69\x67\x68\x74",
                  "\x73\x63\x72\x6F\x6C\x6C\x54\x6F\x70",
                  "\x74\x6F\x70",
                  "\x70\x6F\x73\x69\x74\x69\x6F\x6E",
                  "\x23\x72\x65\x73\x75\x6C\x74\x61\x74",
                  "\x3C\x75\x6C\x3E\x3C\x6C\x69\x3E\x3C\x61\x20\x68\x72\x65\x66\x3D\x27\x23\x27\x3E\x4C\x69\x6E\x6B\x20\x31\x3C\x2F\x61\x3E\x3C\x2F\x6C\x69\x3E\x3C\x6C\x69\x3E\x3C\x61\x20\x68\x72\x65\x66\x3D\x27\x23\x27\x3E\x4C\x69\x6E\x6B\x20\x32\x3C\x2F\x61\x3E\x3C\x2F\x6C\x69\x3E\x3C\x6C\x69\x3E\x3C\x61\x20\x68\x72\x65\x66\x3D\x27\x23\x27\x3E\x4C\x69\x6E\x6B\x20\x33\x3C\x2F\x61\x3E\x3C\x2F\x6C\x69\x3E\x3C\x2F\x75\x6C\x3E",
                  "\x61\x70\x70\x65\x6E\x64", "\x23\x6C\x69\x6E\x6B\x73",
                  "\x30", "\x63\x73\x73"];
              $(document)[_0x38ec[1]](function () {
                  $(window)[_0x38ec[0]](function () {
                      checkOffsetAndLoad()
                  });
                  checkOffsetAndLoad()
              });
              var linksLoaded = false;
              var commentsLoaded = false;

              function checkOffsetAndLoad() {
                  var _0x8f3ex4 = $(window)[_0x38ec[2]]()
                      + $(window)[_0x38ec[3]]();
                  var _0x8f3ex5 = $(_0x38ec[6])[_0x38ec[5]]()[_0x38ec[4]] + 200;
                  if (_0x8f3ex4 > _0x8f3ex5 && !linksLoaded) {
                      $(_0x38ec[9])[_0x38ec[8]](_0x38ec[7]);
                      $(_0x38ec[9])[_0x38ec[11]]
                      ({
                          '\x6D\x69\x6E\x2D\x68\x65\x69\x67\x68\x74': _0x38ec[10]
                      });
                      linksLoaded = true
                  }
              }
          </script>-->
    </div>
    <noscript>Votre navigateur ne supporte pas JavaScript !</noscript>
</div>
</body>
</html>