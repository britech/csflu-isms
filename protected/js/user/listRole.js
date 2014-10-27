$(document).ready(function(){
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields:[
            {name: 'name'},
            {name: 'action'},
            {name: 'id'}
        ],
        id: 'id',
        url: '?r=role/renderSecurityRoleGrid'
    });

    $("#securityRoleList").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Security Role</span>', dataField: 'name'},
            {text: '', dataField: 'action', width: '20%'}
        ],
        initRowDetails: function(id, row, element, rowinfo){
            rowinfo.detailsHeight = '500';
            $.post('?r=role/getSecurityRole', 
            {id:id},
            function(data){
                element.html(data);
            });
        },
        rowDetails: true,
        width: '100%',
        pageable: true,
        filterable: true,
        filterMode: 'simple'
    });
});