$(document).ready(function() {
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields: [
            {name: 'year'},
            {name: 'group'},
            {name: 'figure'}
        ],
        url: '?r=km/renderIndicatorBaselineTable',
        type: 'POST',
        data: {
            id: $("#indicator-id").val(),
            action: 'display'
        }
    });

    $("#baselineTable").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        groups: ['year'],
        groupsRenderer: function(value, rowData, level){
            return "Year Covered:&nbsp;" + value;
        },
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Item</span>', dataField: 'group', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Value</span>', dataField: 'figure'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 50
    });
});