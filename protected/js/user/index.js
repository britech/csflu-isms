$(document).ready(function() {
   
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields:[
            {name: 'name'},
            {name: 'status'},
            {name: 'action'}
        ],
        url: '?r=user/listEmployees'
    });

    $("#employeeList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Name</span>', dataField: 'name', width: '60%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status'},
            {text: '', dataField: 'action', width: '20%'}
        ],
        width: '100%',
        pageable: true,
        filterable: true,
        filterMode: 'simple'
    });
});
