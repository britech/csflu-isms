$(document).ready(function() {

    $("#ongoing").click(function() {
        $("#status").val('A');
        $("#text").html("Change status to: <strong>ONGOING</strong>");
        $("#dialog-status").jqxWindow('open');
    });

    $("#pending").click(function() {
        $("#status").val('P');
        $("#text").html("Change status to: <strong>PENDING</strong>");
        $("#dialog-status").jqxWindow('open');
    });

    $("#dialog-status").jqxWindow({
        title: '<strong>Confirm Commitment Status Update</strong>',
        width: 500,
        height: 200,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")
    });

    $("#accept").click(function() {
        var id = $("#activity").val();
        var status = $("#status").val();
        var period = $("#period").val();

        $.post("?r=activity/updateStatus",
                {id: id, status: status, period: period},
        function(data) {
            try {
                var response = $.parseJSON(data);
                window.location = response.url;
            } catch(e){
                console.log(e);
            }
        });
    });
});