$(document).ready(function() {
    $("[id^=profileList]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'perspective'},
                {name: 'objective'},
                {name: 'indicator'},
                {name: 'action'}
            ],
            url: '?r=scorecard/listLeadMeasures',
            type: 'POST',
            data: {
                map: $("[id^=profileList]").attr('id').split("-")[1],
                readonly: 0
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure</span>', dataField: 'indicator', width: '75%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'action', width: '25%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['perspective', 'objective'],
        groupsRenderer: function(value, rowData, level) {
            if (level === 0) {
                return "<strong>" + value + "</strong>";
            } else if (level === 1) {
                return "<strong style=\"margin-left: 20px;\">" + value + "</strong>";
            }
        }
    });

    $("#refresh").click(function() {
        $("[id^=profileList]").jqxDataTable('updateBoundData');
    });
});