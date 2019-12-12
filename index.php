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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"
            type="text/javascript"></script>
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
            <input id="BoutonsGenerique" name="BoutonsGenerique" type="submit" value="Demander les termes génériques"
                   class="btn btn-primary"/>
            <fieldset>
                <legend> Options</legend>
                <div class="row">
                    <label>
                        <select id="rel" name="rel" class="cus
                        tom-select col-md">
                            <option selected="">Choix Relation</option>
                            <option value="75">r_accomp</option>
                            <option value="40">r_action-verbe</option>
                            <option value="31">r_action_lieu</option>
                            <option value="154">r_activ_voice</option>
                            <option value="159">r_adj-adv</option>
                            <option value="157">r_adj-nomprop</option>
                            <option value="43">r_adj-verbe</option>
                            <option value="164">r_adj>nom</option>
                            <option value="160">r_adv-adj</option>
                            <option value="55">r_against</option>
                            <option value="56">r_against-1</option>
                            <option value="13">r_agent</option>
                            <option value="24">r_agent-1</option>
                            <option value="38">r_agentif_role</option>
                            <option value="63">r_agentive_implication</option>
                            <option value="666">r_aki</option>
                            <option value="998">r_annotation</option>
                            <option value="997">r_annotation_exception</option>
                            <option value="7">r_anto</option>
                            <option value="0">r_associated</option>
                            <option value="150">r_beneficiaire</option>
                            <option value="119">r_but</option>
                            <option value="120">r_but-1</option>
                            <option value="102">r_can_eat</option>
                            <option value="17">r_carac</option>
                            <option value="23">r_carac-1</option>
                            <option value="42">r_causatif</option>
                            <option value="66">r_chunk_head</option>
                            <option value="48">r_chunk_instr</option>
                            <option value="47">r_chunk_loc</option>
                            <option value="46">r_chunk_objet</option>
                            <option value="29">r_chunk_pred</option>
                            <option value="45">r_chunk_sujet</option>
                            <option value="107">r_cible</option>
                            <option value="135">r_circumstance</option>
                            <option value="78">r_cohypo</option>
                            <option value="106">r_color</option>
                            <option value="118">r_comparison</option>
                            <option value="149">r_compl_agent</option>
                            <option value="163">r_concerning</option>
                            <option value="41">r_conseq</option>
                            <option value="200">r_context</option>
                            <option value="555">r_cooccurrence</option>
                            <option value="18">r_data</option>
                            <option value="104">r_deplac_mode</option>
                            <option value="99">r_der_morpho</option>
                            <option value="151">r_descend_de</option>
                            <option value="110">r_diagnostique</option>
                            <option value="3">r_domain</option>
                            <option value="27">r_domain-1</option>
                            <option value="152">r_domain_subst</option>
                            <option value="61">r_equiv</option>
                            <option value="33">r_error</option>
                            <option value="22">r_family</option>
                            <option value="60">r_fem</option>
                            <option value="12">r_flpot</option>
                            <option value="117">r_foncteur</option>
                            <option value="103">r_has_actors</option>
                            <option value="21">r_has_antimagn</option>
                            <option value="100">r_has_auteur</option>
                            <option value="64">r_has_instance</option>
                            <option value="105">r_has_interpret</option>
                            <option value="20">r_has_magn</option>
                            <option value="9">r_has_part</option>
                            <option value="101">r_has_personnage</option>
                            <option value="10">r_holo</option>
                            <option value="161">r_homophone</option>
                            <option value="8">r_hypo</option>
                            <option value="57">r_implication</option>
                            <option value="127">r_incompatible</option>
                            <option value="36">r_infopot</option>
                            <option value="999">r_inhib</option>
                            <option value="16">r_instr</option>
                            <option value="25">r_instr-1</option>
                            <option value="6">r_isa</option>
                            <option value="126">r_isa-incompatible</option>
                            <option value="74">r_is_bigger_than</option>
                            <option value="131">r_is_concerned_by</option>
                            <option value="130">r_is_instance_of</option>
                            <option value="73">r_is_smaller_than</option>
                            <option value="156">r_is_used_by</option>
                            <option value="69">r_item>set</option>
                            <option value="19">r_lemma</option>
                            <option value="15">r_lieu</option>
                            <option value="28">r_lieu-1</option>
                            <option value="30">r_lieu_action</option>
                            <option value="444">r_link</option>
                            <option value="116">r_linked-with</option>
                            <option value="11">r_locution</option>
                            <option value="53">r_make</option>
                            <option value="155">r_make_use_of</option>
                            <option value="34">r_manner</option>
                            <option value="62">r_manner-1</option>
                            <option value="59">r_masc</option>
                            <option value="51">r_mater>object</option>
                            <option value="35">r_meaning / glose</option>
                            <option value="128">r_node2relnode</option>
                            <option value="165">r_nom>adj</option>
                            <option value="158">r_nomprop-adj</option>
                            <option value="50">r_object>mater</option>
                            <option value="166">r_opinion_of</option>
                            <option value="121">r_own</option>
                            <option value="122">r_own-1</option>
                            <option value="14">r_patient</option>
                            <option value="26">r_patient-1</option>
                            <option value="4">r_pos</option>
                            <option value="162">r_potential_confusion</option>
                            <option value="124">r_predecesseur-logic</option>
                            <option value="111">r_predecesseur-space</option>
                            <option value="109">r_predecesseur-time</option>
                            <option value="70">r_processus>agent</option>
                            <option value="80">r_processus>instr</option>
                            <option value="76">r_processus>patient</option>
                            <option value="54">r_product_of</option>
                            <option value="134">r_promote</option>
                            <option value="153">r_prop</option>
                            <option value="58">r_quantificateur</option>
                            <option value="2">r_raff_morpho</option>
                            <option value="1">r_raff_sem</option>
                            <option value="129">r_require</option>
                            <option value="32">r_sentiment</option>
                            <option value="115">r_sentiment-1</option>
                            <option value="68">r_set>item</option>
                            <option value="67">r_similar</option>
                            <option value="113">r_social_tie</option>
                            <option value="125">r_successeur-logic</option>
                            <option value="112">r_successeur-space</option>
                            <option value="52">r_successeur-time</option>
                            <option value="108">r_symptomes</option>
                            <option value="132">r_symptomes-1</option>
                            <option value="5">r_syn</option>
                            <option value="72">r_syn_strict</option>
                            <option value="37">r_telic_role</option>
                            <option value="49">r_time</option>
                            <option value="333">r_translation</option>
                            <option value="114">r_tributary</option>
                            <option value="133">r_units</option>
                            <option value="71">r_variante</option>
                            <option value="39">r_verbe-action</option>
                            <option value="44">r_verbe-adj</option>
                            <option value="123">r_verb_aux</option>
                            <option value="77">r_verb_ppas</option>
                            <option value="79">r_verb_ppre</option>
                            <option value="65">r_verb_real</option>
                            <option value="777">r_wiki</option>
                        </select>
                    </label>
                    <div class="checkbox col-md">
                        <input id="relationSortante" name="relationSortante" type="checkbox" value="norelout"/>
                        <label for="relationSortante">Pas de relations sortantes</label>
                    </div>

                    <div class="checkbox col-md">
                        <input id="relationEntrante" name="relationEntrante" type="checkbox" value="norelin"/>
                        <label for="relationEntrante">Pas de relations entrantes</label>
                    </div>
                    <div class="checkbox col-xl">
                        <input id="trieAlphabetique" name="trieAlphabetique" type="checkbox" value="tAlpha"/>
                        <label for="trieAlphabetique">Résultats triés alphabétiquement</label>
                    </div>
                </div>
            </fieldset>
        </div>
    </form>
    <div class="container-fluid" id="resultat">
        <!--        <div class="row">-->
        <?php
        include('traitement.php');
        ?>
        <!--        </div>-->
        <div id="resultat_Final" class="row">
            <script src="JS/fonction.js"></script>
            <script>
                let terme = "<?php echo !empty($_POST['champRecherche']) ? htmlspecialchars($_POST['champRecherche']) : '' ?>";
                let generique = <?php echo !empty($_POST['BoutonsGenerique']) ? 1 : 0 ?>;

                if (!generique)
                    infiniteScroll(terme);
            </script>
        </div>
    </div>
    <noscript>Votre navigateur ne supporte pas JavaScript !</noscript>
</div>
</body>
</html>