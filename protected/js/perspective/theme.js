$(document).ready(function() {
    $("#description-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'}
            ],
            url: '?r=theme/listThemes',
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
    }).on("bindingComplete", function() {
        $("#description-input").val($("#description").val());
    });

    $("#description-input").change(function() {
        $("#description").val($(this).val());
    }).blur(function() {
        $("#description").val($(this).val());
    });

    $('#deleteTheme').jqxWindow({
        title: '<strong>Confirm Theme Deletion</strong>',
        width: 300,
        height: 140,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")

    });
    $("[id^=del-]").click(function() {
        var text = $(this).parent().siblings("td").html();
        $("#text").html("Do you want to delete the theme, <strong>" + text + "</strong>? Continuing will remove this theme from the Strategy Map.")
        $("#deleteTheme").jqxWindow('open');
        $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
    });

    $("[id^=accept]").click(function() {
        var id = $(this).attr('id').split('-')[1];

        $.post("?r=theme/delete",
                {id: id},
        function(data) {
            var response = $.parseJSON(data);
            window.location = response.url;
        });
    });
});



