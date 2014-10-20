$(document).ready(function(){
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields:[
            {name: 'name'},
            {name: 'action'}
        ],
        url: '?r=role/renderSecurityRoleGrid'
    });

    $("#securityRoleList").jqxGrid({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Security Role</span>', dataField: 'name'},
            {text: '', dataField: 'action', width: '20%'}
        ]
    });
});