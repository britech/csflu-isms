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
    });

    $('#reset-password').jqxWindow({
        title: '<strong>Confirm Password Reset</strong>',
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny-reset")
    });

    $("#reset").click(function() {
        $("#reset-password").jqxWindow('open');
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
                $("#reset").jqxWindow('close');
            }

        });
    });
});