$(document).ready(function() {

    $("[name*=description]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    $("#validation-container").hide();

    $(".ink-form").submit(function() {
        var input = $("[name*=description]").val();
        if (input === '') {
            $("#validation-container").show();
            $("#validation-content").html("Lead Measures should be defined");
            return false;
        } else {
            var data = input.split("+");
            if (data.length > 2) {
                $("#validation-container").show();
                $("#validation-content").html("Lead Measures should be defined");
                return false;
            } else {
                return true;
            }
        }
    });
});