$(document).ready(function() {
    $("[id^=baseline]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'year'},
                {name: 'group'},
                {name: 'figure'}
            ],
            url: '?r=indicator/listBaselines',
            type: 'POST',
            data: {
                id: $("[id^=baseline]").attr('id').split('-')[1],
                action: '0'
            }
        }),
        columnsresize: false,
        theme: 'office',
        groups: ['year'],
        groupsRenderer: function(value, rowData, level) {
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
    
    $("[id^=leadoffice]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'department'},
                {name: 'designation'},
            ],
            url: '?r=measure/listLeadOffices',
            type: 'POST',
            data: {
                profile: $("[id^=leadoffice]").attr('id').split('-')[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        groups: ['designation'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";
        },
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Office</span>', dataField: 'department'},
        ],
        width: '100%',
        pageable: true
    });
    $("[id^=target]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'year'},
                {name: 'group'},
                {name: 'value'}
            ],
            url: '?r=measure/listTargets',
            type: 'POST',
            data: {
                profile: $("[id^=target]").attr('id').split('-')[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        groups: ['year'],
        groupsRenderer: function(value, rowData, level) {
            return "Year Covered:&nbsp;" + value;
        },
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Item</span>', dataField: 'group', width: '20%', cellsAlign: 'right'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Value</span>', dataField: 'value'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 50
    });
});