function definition(terme) {
    $('#definition #loader').fadeIn(400);
    $.post('definition.php', {champRecherche: terme}, function (data) {
            if (data !== '') {
                $('#definition summary').replaceWith(data);
                $('#definition .hidden').fadeIn(400);
            }
        }
    );
}