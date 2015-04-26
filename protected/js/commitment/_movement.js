$(document).ready(function() {

    $("[name*=notes]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=commitment/validateCommitmentMovement",
            data: {
                "CommitmentMovement": {
                    'movementFigure': $("[name*=movementFigure]").val(),
                    'notes': $("[name*=notes]").val()
                }
            },
            async: false,
            success: function(data) {
                try {
                    response = $.parseJSON(data);
                    result = response.respCode === '00';
                } catch (e) {
                    $("#validation-container").html(data);
                }
            }
        });

        return result;
    });
});