$(document).ready(function(){
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields:[
            {name: 'name'},
            {name: 'action'}
        ],
        url: '?r=position/renderPositionGrid'
    });

    $("#positionList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Name</span>', dataField: 'name'},
            {text: '', dataField: 'action', width: '20%'}
        ],
        width: '100%',
        pageable: true,
        filterable: true,
        filterMode: 'simple'
    });
});