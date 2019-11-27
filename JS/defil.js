function Go(terme) {
    document.getElementById("champRecherche").value = terme;
    document.forms["rechercheTermes"].submit();
}

function sleep(miliseconds) {
    var currentTime = new Date().getTime();

    while (currentTime + miliseconds >= new Date().getTime()) {
    }
}

function infiniteScroll(terme) {
    // on initialise ajaxready à true au premier chargement de la fonction
    $(window).data('ajaxready', true);

    $('#resultat_Final').append('<div id="loader"><p style="text-align:center"><img src="./Image/ajax-loader.gif" alt="loader ajax"></p></div>');

    let deviceAgent = navigator.userAgent.toLowerCase();
    let agentID = deviceAgent.match(/(iphone|ipod|ipad)/);

    $(window).scroll(function () {
        // On teste si ajaxready vaut false, auquel cas on stoppe la fonction
        if ($(window).data('ajaxready') === false) return;

        if (($(window).scrollTop() + $(window).height()) >= $(document).height()
            || agentID && ($(window).scrollTop() + $(window).height()) + 150 > $(document).height()) {
            // lorsqu'on commence un traitement, on met ajaxready à false
            $(window).data('ajaxready', false);

            $('#resultat_Final #loader').fadeIn(400);
            $.post('relationSortante.php', {champRecherche: terme}, function (data) {
                if (data !== '') {
                    $('#resultat_Final #loader').before(data);
                    $('#resultat_Final .hidden').fadeIn(400);
                    // une fois tous les traitements effectués,
                    // on remet ajaxready à false
                    // afin de pouvoir rappeler la fonction
                    $(window).data('ajaxready', true);
                }
            });
        }
    });
}