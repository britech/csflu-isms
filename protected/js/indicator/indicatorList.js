$(document).ready(function(){
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields:[
            {name: 'description'},
            {name: 'action'}
        ],
        url: '?r=km/renderIndicatorGrid'
    });

    $("#indicatorList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Indicator</span>', dataField: 'description'},
            {text: '', dataField: 'action', width: '30%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        filterable: true,
        filterMode: 'simple'
    });
});