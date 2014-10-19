$(document).ready(function(){
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields:[
            {name: 'role'},
            {name: 'department'},
            {name: 'position'},
            {name: 'link'}
        ],
        url: '?r=user/renderAccountGrid',
        type: 'POST',
        data: {
            id: $("#employee").val()
        }
    });

    $("#accountList").jqxGrid({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Security Role</span>', dataField: 'role', width: '30%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Department</span>', dataField: 'department', width: '40%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Position</span>', dataField: 'position', width: '20%'},
            {text: '', dataField: 'link',  width: '10%'}
        ],
        width: '100%'
    });
});