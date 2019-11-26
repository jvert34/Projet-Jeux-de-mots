$(document).ready(function () {
    $(window).scroll(function () {
        checkOffsetAndLoad();
    });
    checkOffsetAndLoad();

});

let linksLoaded = false;

function checkOffsetAndLoad() {
    let scrollTop = $(window).height() + $(window).scrollTop();
    let linksTop = $('#resultat').position().top + 200;
    // +200 uniquement en mode dev pour voir si le script fonctionne ! Enlever dans PROD

    if (scrollTop > linksTop && !linksLoaded) {
        $('#links').append("<ul><li><a href='#'>Link 1</a></li><li><a href='#'>Link 2</a></li><li><a href='#'>Link 3</a></li></ul>");
        // $('#links').css({'min-height': '0'});
        linksLoaded = true;
    }
}