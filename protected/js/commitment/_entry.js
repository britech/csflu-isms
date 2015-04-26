$(document).ready(function() {

    $("[id^=remove]").click(function() {
        var commitment = $("#commitment").val();
        var id = $(this).attr('id').split("-")[1];
        $("#text-delete").html("Do you want to delete <strong>" + commitment + "</strong> from your declared commitments?");
        $("#dialog-delete").jqxWindow('open');
        $("#accept-delete").prop('id', "accept-delete-" + id);
    });

    $('#dialog-delete').jqxWindow({
        title: '<strong>Confirm Commitment Deletion</strong>',
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

    $("[id^=accept-delete]").click(function() {
        var id = $(this).attr('id').split("-")[2];
        $.post("?r=commitment/deleteEntry",
                {
                    id: id
                },
        function(data) {
            var url = "";
            try {
                var response = $.parseJSON(data);
                url = response.url;
            } catch (e) {
                url = "?r=ip/index";
            }
            window.location = url;
        });
    });

    $("#validation-container").hide();

    $(".ink-form").submit(function() {
        var data = $("#commitment").val();

        if (data === '' || data.length === 0) {
            $("#validation-container").show();
            $("#validation-message").html("*&nbsp;Commitment should be defined");
            return false;
        } else {
            return true;
        }
    });

});