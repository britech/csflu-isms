$(document).ready(function() {
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields: [
            {name: 'description'}
        ],
        url: '?r=indicator/listIndicators'
    });

    $("#indicatorList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Indicator</span>', dataField: 'description'}
        ],
        width: '100%',
        pageable: true,
        filterable: true,
        filterMode: 'simple'
    });
});