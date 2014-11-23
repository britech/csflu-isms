$(document).ready(function() {
    $("[id^=pers]").click(function() {
        var id = $(this).attr('id').split("-")[1];
        window.location = '?r=map/updatePerspective&id='+id;
    });
});


