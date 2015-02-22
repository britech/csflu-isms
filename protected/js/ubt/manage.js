$(document).ready(function() {
    $("[id^=ubtList]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'},
                {name: 'status'},
                {name: 'action'}
            ],
            url: '?r=ubt/listUnitBreakthroughsByDepartment',
            type: 'POST',
            data: {
                department: $("[id^=ubtList]").attr('id').split("-")[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Unit Breakthrough</span>', dataField: 'description', width: '50%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', cellsAlign: 'center' ,width: '10%'},
            {text: '', dataField: 'action', width: '40%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        sortable: true,
        selectionMode: 'singleRow'
    });
});