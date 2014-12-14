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
            url: "?r=perspective/validate",
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
            url: '?r=perspective/listPerspectives',
            type: 'POST'
        }),
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("#description").val(event.args.item.label);
        }
    });

    $("#description-input").change(function() {
        $("#description").val($(this).val());
    });

    $('#deletePerspective').jqxWindow({
        title: '<strong>Confirm Perspective Deletion</strong>',
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")
    });

    $("[id^=del-]").click(function() {
        var text = $(this).parent().siblings("td + td").html();
        $("#text").html("Do you want to delete the perspective, <strong>" + text + "</strong>? Continuing will remove this perspective and its underlying objectives from the Strategy Map.")
        $("#deletePerspective").jqxWindow('open');
        $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
    });

    $("[id^=accept]").click(function() {
        var id = $(this).attr('id').split('-')[1];

        $.post("?r=perspective/delete",
                {id: id},
        function(data) {
            var response = $.parseJSON(data);
            window.location = response.url;
        });
    });
});