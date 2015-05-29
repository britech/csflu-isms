$(document).ready(function() {
    $("#movement-log").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'logStamp', type: 'string'},
                {name: 'user', type: 'string'},
                {name: 'logs', type: 'string'}
            ],
            url: '?r=scorecard/listMovements',
            type: 'POST',
            data: {
                measure: $("#profile").val(),
                period: $("#period").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Timestamp</span>', dataField: 'logStamp', width: '33%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Entered By</span>', dataField: 'user', width: '33%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Remarks</span>', dataField: 'logs', width: '34%'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 10,
        sortable: true,
        selectionMode: 'singleRow'
    });
    
    $("#refresh").click(function(){
        $("#movement-log").jqxDataTable('updatebounddata');
    });
});