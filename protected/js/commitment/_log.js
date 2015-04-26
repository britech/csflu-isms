$(document).ready(function(){
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields:[
            {name: 'date_entered'},
            {name: 'figure'},
            {name: 'notes'}
        ],
        url: '?r=commitment/listCommitments',
        type: 'POST',
        data: {
            commitment: $("#commit-id").val()
        }
    });

    $("#movement-log").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Date Entered</span>', dataField: 'date_entered', width: '15%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Description</span>', dataField: 'figure', width: '40%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Notes</span>', dataField: 'notes'}
        ],
        width: '100%',
        pageable: true,
        sortable: true
       
    });
});