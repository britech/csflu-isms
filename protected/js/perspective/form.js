$(document).ready(function() {
    $("#positionOrder").jqxNumberInput({
        inputMode: 'simple',
        spinButtons: true,
        min: 1,
        max: 5,
        decimalDigits: 0,
        height: '35px',
        textAlign: 'left',
        width: '150px'
    }).val();

    $("#positionOrder").on('valuechanged', function(event) {
        $("#position-order").val(event.args.value);
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=map/validatePerspective",
            data: {"Perspective": {
                    'description': $("[name*=description]").val(),
                    'positionOrder': $("[name*=positionOrder]").val()},
                "mode": 1},
            async: false,
            success: function(data) {
                try {
                    $.parseJSON(data);
                    result = true;
                } catch (e) {
                    $("#validation-container").html(data);
                }
            }
        });

        return result;
    });


});