$(document).ready(function() {
    $("#search").keyup(function() {
        var query = $(this).val();
        if (query != '') {
            $.ajax({
                url: 'baza.php',
                method: 'POST',
                data: {
                    query: query
                },
                success: function(data) {
                    $('#searchResults').html(data);
                }
            });
        }
    });
});