$(document).ready(function() {
    $("[name*=notes]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    $("#value-input").jqxNumberInput({
        theme: 'office',
        height: '40px',
        textAlign: 'left',
        digits: 12,
        symbol: $("#uom").val(),
        symbolPosition: 'right'
    }).on("valuechanged", function() {
        $("[name*=movementValue]").val($("#value-input").val());
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=scorecard/validateMovementInput",
            data: {
                "MeasureProfileMovement": {
                    'movementValue': $("[name*=movementValue]").val(),
                    'periodDate': $("[name*=periodDate]").val()
                },
                "MeasureProfileMovementLog": {
                    'notes': $("[name*=notes]").val()
                },
                "UserAccount": {
                    'id': $("#user").val()
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