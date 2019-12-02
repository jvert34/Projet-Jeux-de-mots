function Go(terme) {
    document.getElementById("champRecherche").value = terme;
    document.forms["rechercheTermes"].submit();
}

function infiniteScroll(terme) {
    // on initialise ajaxready à true au premier chargement de la fonction
    $(window).data('ajaxready', true);
    $(window).data('relationS', true);

    $('#resultat_Final').append('<div id="loader"><div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div></div>');

    let deviceAgent = navigator.userAgent.toLowerCase();
    let agentID = deviceAgent.match(/(iphone|ipod|ipad)/);

    $(window).scroll(function () {
        // On teste si ajaxready vaut false, auquel cas on stoppe la fonction
        if (($(window).data('ajaxready') === false) && ($(window).data('relationS') === false))
            return;

        function extracted(programme) {
            if (($(window).scrollTop() + $(window).height()) + 1 >= $(document).height()
                || agentID && ($(window).scrollTop() + $(window).height()) + 150 > $(document).height()) {
                // lorsqu'on commence un traitement, on met ajaxready à false
                $(window).data('ajaxready', false);
                $('#resultat_Final #loader').fadeIn(400);
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

        if ($(window).data('relationS') === true) {
            extracted('relationSortante.php');
        } else {
            extracted('relationEntrante.php');
            $('#resultat_Final #loader').fadeOut(400);
        }
    });
}