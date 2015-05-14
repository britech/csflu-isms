$(document).ready(function() {
    $("[id^=initiativeList]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'},
                {name: 'status'},
                {name: 'map'},
                {name: 'action'}
            ],
            url: '?r=initiative/listInitiativesByImplementingOffice',
            type: 'POST',
            data: {
                department: $("[id^=initiativeList]").attr('id').split("-")[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Initiative</span>', dataField: 'description', width: '80%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', cellsAlign: 'center', width: '10%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['map'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";
        }
    });

    $("#refresh").click(function() {
        $("[id^=initiativeList]").jqxDataTable('updateBoundData');
    });
});