$(document).ready(function() {
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields: [
            {name: 'role'},
            {name: 'department'},
            {name: 'position'},
            {name: 'link'}
        ],
        url: '?r=user/renderAccountGrid',
        type: 'POST',
        data: {
            id: $("#employee").val()
        }
    });

    $("#accountList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Security Role</span>', dataField: 'role', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Department</span>', dataField: 'department', width: '30%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Position</span>', dataField: 'position', width: '30%'},
            {text: '', dataField: 'link', width: '20%'}
        ],
        width: '100%',
        pageable: true,
        sortable: true
    }).on("rowClick", function(event) {
        var args = event.args;
        var row = args.row;
        $("[id^=unlink-]").click(function() {
            var id = $(this).attr('id').split('-')[1];
            $("#text").html("Unlinking the Security Role<br/><br/>Department:&nbsp;<strong>" + row.department + "</strong><br/>Role:&nbsp<strong>" + row.role + "</strong><br/><br/>Do you want to continue?");
            $("#accept-delete").attr('id', "accept-delete-" + id);
            $("#delete-account").jqxWindow('title', '<strong>Confirm Unlinking of Security Role</strong>');
            $("#delete-account").jqxWindow('open');
        });
    });

    $('#reset-password, #disable-account, #activate-account').jqxWindow({
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none'
    });

    $('#delete-account').jqxWindow({
        width: 500,
        height: 200,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none'
    });

    $("#reset").click(function() {
        $("#reset-password").jqxWindow('title', '<strong>Confirm Password Reset</strong>');
        $("#reset-password").jqxWindow('open');
    });

    $("#deny-reset").click(function() {
        $("#accountList").jqxDataTable('updatebounddata');
        $("#reset-password").jqxWindow('close');
    });

    $("#accept-reset").click(function() {
        var id = $("#employee").val();
        $.post("?r=user/resetPassword",
                {id: id},
        function(data) {
            try {
                var response = $.parseJSON(data);
                window.location = response.url;
            } catch (e) {
                console.log(e);
                $("#reset-password").jqxWindow('close');
            }

        });
    });

    $("#disable").click(function() {
        $("#disable-account").jqxWindow('title', '<strong>Confirm Deactivation of User Account</strong>');
        $("#disable-account").jqxWindow('open');
    });

    $("#deny-disable").click(function() {
        $("#accountList").jqxDataTable('updatebounddata');
        $("#disable-account").jqxWindow('close');
    });

    $("#accept-disable").click(function() {
        var id = $("#employee").val();
        $.post("?r=user/toggleAccountStatus",
                {id: id,
                    status: '0'},
        function(data) {
            try {
                var response = $.parseJSON(data);
                window.location = response.url;
            } catch (e) {
                console.log(e);
                $("#disable-account").jqxWindow('close');
            }

        });
    });

    $("#activate").click(function() {
        $("#activate-account").jqxWindow('title', '<strong>Confirm Activation of User Account</strong>');
        $("#activate-account").jqxWindow('open');
    });

    $("#deny-activate").click(function() {
        $("#accountList").jqxDataTable('updatebounddata');
        $("#activate-account").jqxWindow('close');
    });

    $("#accept-activate").click(function() {
        var id = $("#employee").val();
        $.post("?r=user/toggleAccountStatus",
                {id: id,
                    status: '1'},
        function(data) {
            try {
                var response = $.parseJSON(data);
                window.location = response.url;
            } catch (e) {
                console.log(e);
                $("#activate-account").jqxWindow('close');
            }

        });
    });

    $("#deny-delete").click(function() {
        $("#accountList").jqxDataTable('updatebounddata');
        $("#delete-account").jqxWindow('close');
    });

    $("[id^=accept-delete]").click(function() {
        var id = $(this).attr('id').split('-')[2];
        $.post("?r=user/deleteLink",
                {id: id},
        function(data) {
            try {
                var response = $.parseJSON(data);
                window.location = response.url;
            } catch (e) {
                console.log(e);
                $("#delete-account").jqxWindow('close');
                $("[id^=accept-delete]").attr('id', 'accept-delete');
            }
        });
    });
});