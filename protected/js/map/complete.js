$(document).ready(function() {
    $("[id^=pers]").click(function() {
        var id = $(this).attr('id').split("-")[1];
        window.location = '?r=map/updatePerspective&id='+id;
    });
    
    $("[id^=theme]").click(function() {
        var id = $(this).attr('id').split("-")[1];
        window.location = '?r=map/updateTheme&id='+id;
    });
    
    $("[id^=obj]").click(function() {
        var id = $(this).attr('id').split("-")[1];
        window.location = '?r=map/updateObjective&id='+id;
    });
});


