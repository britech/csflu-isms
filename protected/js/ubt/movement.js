$(document).ready(function() {
    $("[id^=log]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'user', type: 'string'},
                {name: 'ubt', type: 'string'},
                {name: 'lm1', type: 'string'},
                {name: 'lm2', type: 'string'},
                {name: 'notes', type: 'string'},
                {name: 'wig', type: 'string'}
            ],
            url: '?r=ubt/listUbtMovements',
            type: 'POST',
            data: {
                ubt: $("[id^=log]").attr("id").split("-")[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Entered By</span>', dataField: 'user', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">UBT Movement</span>', dataField: 'ubt', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure 1 Movement</span>', dataField: 'lm1', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure 2 Movement</span>', dataField: 'lm2', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Notes</span>', dataField: 'notes', width: '20%'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 10,
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['wig'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";
        }
    });

    $("#refresh").click(function() {
        $("[id^=log]").jqxDataTable('updateBoundData');
    });
});