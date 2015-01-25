$(document).ready(function() {
    $("#phaseNumber-input").jqxNumberInput({
        inputMode: 'simple',
        spinButtons: true,
        min: 1,
        max: 100,
        decimalDigits: 0,
        height: '35px',
        textAlign: 'left',
        width: '150px'
    }).on("valuechanged", function(event) {
        $("[name*=phaseNumber]").val(event.args.value);
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=project/validatePhaseInput",
            data: {
                "Phase": {
                    'phaseNumber': $("[name*=phaseNumber]").val(),
                    'title': $("[name*=title]").val(),
                    'description': $("[name*=description]").val()
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