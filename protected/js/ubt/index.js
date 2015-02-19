$(document).ready(function() {
    $("[id^=ubtList]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'},
                {name: 'unit'},
                {name: 'action'}
            ],
            url: '?r=ubt/listUnitBreakthroughsByStrategyMap',
            type: 'POST',
            data: {
                map: $("[id^=ubtList]").attr('id').split("-")[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Unit Breakthrough</span>', dataField: 'description', width: '90%'},
            {text: '', dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['unit'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";
        }
    });
});