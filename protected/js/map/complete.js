$(document).ready(function() {
    $("[id^=pers]").click(function() {
        var id = $(this).attr('id').split("-")[1];
        window.location = '?r=perspective/update&id='+id;
    });
    
    $("[id^=theme]").click(function() {
        var id = $(this).attr('id').split("-")[1];
        window.location = '?r=theme/update&id='+id;
    });
    
    $("[id^=obj]").click(function() {
        var id = $(this).attr('id').split("-")[1];
        window.location = '?r=objective/update&id='+id;
    });
});


