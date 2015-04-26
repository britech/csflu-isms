$(document).ready(function() {

    $("[id^=ongoing]").click(function() {
        var commitment = $("#commitment").val();
        var id = $(this).attr('id').split("-")[1];
        $("#text-ongoing").html("Do you want to set <strong>" + commitment + "</strong> to <strong>Ongoing</strong> status");
        $("#dialog-ongoing").jqxWindow('open');
        $("#accept-ongoing").prop('id', "accept-ongoing-" + id);
    });

    $("#dialog-ongoing").jqxWindow({
        title: '<strong>Confirm Commitment Status Update</strong>',
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny-ongoing")
    });

    $("[id^=accept-ongoing]").click(function() {
        var id = $(this).attr('id').split("-")[2];
        var commitment = $("#commitment").val();
        var user = $("#user").val();
        $.post("?r=commitment/updateEntry&remote=1",
                {
                    "Commitment": {
                        'id': id,
                        'commitment': commitment,
                        'commitmentEnvironmentStatus': 'a'
                    },
                    "UserAccount": {
                        'id': user
                    }
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
    
    $("[id^=pending]").click(function() {
        var commitment = $("#commitment").val();
        var id = $(this).attr('id').split("-")[1];
        $("#text-pending").html("Do you want to set <strong>" + commitment + "</strong> to <strong>Pending</strong> status");
        $("#dialog-pending").jqxWindow('open');
        $("#accept-pending").prop('id', "accept-pending-" + id);
    });
    
    $("#dialog-pending").jqxWindow({
        title: '<strong>Confirm Commitment Status Update</strong>',
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny-pending")
    });

    $("[id^=accept-pending]").click(function() {
        var id = $(this).attr('id').split("-")[2];
        var commitment = $("#commitment").val();
        var user = $("#user").val();
        $.post("?r=commitment/updateEntry&remote=1",
                {
                    "Commitment": {
                        'id': id,
                        'commitment': commitment,
                        'commitmentEnvironmentStatus': 'p'
                    },
                    "UserAccount": {
                        'id': user
                    }
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
});