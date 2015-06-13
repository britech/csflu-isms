$(document).ready(function() {
    $("#revisions").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'logStamp', type: 'string'},
                {name: 'user', type: 'string'},
                {name: 'action', type: 'string'},
                {name: 'notes', type: 'string'}
            ],
            url: '?r=revision/retrieveList',
            type: 'POST',
            data: {
                module: $("#module").val(),
                id: $("#id").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Timestamp</span>', dataField: 'logStamp', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">User</span>', dataField: 'user', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Action Type</span>', dataField: 'action', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Notes</span>', dataField: 'notes', width: '40%'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 10,
        sortable: true,
        selectionMode: 'singleRow'
    });
    
    $("#refresh").click(function(){
        $("#revisions").jqxDataTable('updatebounddata');
    });
});