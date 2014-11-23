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
    });

    $("#positionOrder").on('valuechanged', function(event) {
        $("#position-order").val(event.args.value);
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=map/validatePerspective",
            data: {"Perspective": {
                    'description': $("#description-input").val(),
                    'positionOrder': $("#positionOrder").val()},
                "mode": 1},
            async: false,
            success: function(data) {
                try {
                    response = $.parseJSON(data);
                    if (response.respCode === '00') {
                        $("#description").val($("#description-input").val());
                        $("#positionOrder").val($("#position-order").val());
                    }
                    result = response.respCode === '00';
                } catch (e) {
                    $("#validation-container").html(data);
                }
            }
        });

        return result;
    });

    $("#description-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'}
            ],
            url: '?r=map/listEnlistedPerspectives'
        }),
        valueMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px'
    }).on("select", function(event) {
        if (event.args) {
            $("#description").val(event.args.item.value);
        }
    });

    $("#description-input").change(function() {
        $("#description").val($(this).val());
    });
});