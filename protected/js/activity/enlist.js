$(document).ready(function() {
    $("[name*=notes]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    $("#budget-input").jqxNumberInput({
        theme: 'office',
        height: '35px',
        textAlign: 'left',
        digits: 12,
        symbol: 'PhP'
    }).on("valuechanged", function(event) {
        $("[name*=budgetAmount]").val($("#budget-input").val());
    });

    $("#figure-input").jqxNumberInput({
        theme: 'office',
        height: '35px',
        textAlign: 'left',
        digits: 12,
        symbol: ''
    }).on("valuechanged", function(event) {
        $("[name*=actualFigure]").val($("#figure-input").val());
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=activity/validateMovementInput",
            data: {
                "ActivityMovement": {
                    'periodDate': $("[name*=periodDate]").val(),
                    'actualFigure': $("[name*=actualFigure]").val(),
                    'budgetAmount': $("[name*=budgetAmount]").val(),
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