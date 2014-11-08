$(document).ready(function() {
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields: [
            {name: 'name'},
            {name: 'type'},
            {name: 'status'}
        ],
        url: '?r=map/renderStrategyMapTable'
    });

    $("#strategyMapList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Name</span>', dataField: 'name', width:'80%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', cellsAlign:'center'}
        ],
        width: '100%',
        pageable: true,
        filterable: true,
        //filterMode: 'simple',
        groups: ['type'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";

        }
    });
});