$(document).ready(function() {
    $("#commitment").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    $("#validation-container").hide();

    $(".ink-form").submit(function() {
        var data = $("#commitment").val();

        if (data.length === 0) {
            $("#validation-container").show().children("#validation-message").html("-&nbsp;Commitment should be defined.");
            return false;
        } else {
            return true;
        }
    });
});