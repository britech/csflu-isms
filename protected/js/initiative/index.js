$(document).ready(function() {
    $("[id^=initiativeList]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'initiative'},
                {name: 'action'}
            ],
            url: '?r=initiative/listInitiatives',
            type: 'POST',
            data: {
                map: $("[id^=initiativeList]").attr('id').split("-")[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Initiative</span>', dataField: 'initiative', width: '90%'},
            {text: '', dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow'
    });
});