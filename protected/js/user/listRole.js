$(document).ready(function() {
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields: [
            {name: 'name'},
            {name: 'action'},
            {name: 'id'}
        ],
        id: 'id',
        url: '?r=role/renderSecurityRoleGrid'
    });

    $("#securityRoleList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Security Role</span>', dataField: 'name'},
            {text: '', dataField: 'action', width: '20%'}
        ],
        initRowDetails: function(id, row, element, rowinfo) {
            rowinfo.detailsHeight = '500';
            $.post('?r=role/getSecurityRole',
                    {id: id},
            function(data) {
                element.html(data);
            });
        },
        rowDetails: true,
        width: '100%',
        pageable: true,
        filterable: true,
        filterMode: 'simple'
    }).on("rowClick", function(event) {
        var args = event.args;
        var row = args.row;
        $("[id^=remove-]").click(function() {
            var id = $(this).attr('id').split('-')[1];
            $("#text").html("Removing the Security Role<br/><br/>Name:&nbsp;<strong>" + row.name + "</strong><br/><strong>WARNING:&nbsp;</strong>Deleting the security role will also remove the accounts linked to it.<br/><br/>Do you want to continue?");
            $("#accept").attr('id', "accept-" + id);
            $("#delete-role").jqxWindow('title', '<strong>Confirm Removal of Security Role</strong>');
            $("#delete-role").jqxWindow('open');
        });
    });

    $('#delete-role').jqxWindow({
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

    $("#deny").click(function() {
        $("#securityRoleList").jqxDataTable('updatebounddata');
    });

    $("[id^=accept]").click(function() {
        var id = $(this).attr('id').split('-')[1];
        $.post("?r=role/remove",
                {id: id},
        function(data) {
            try {
                var response = $.parseJSON(data);
                window.location = response.url;
            } catch (e) {
                console.log(e);
                $("#delete-role").jqxWindow('close');
            }

        });
    });
});