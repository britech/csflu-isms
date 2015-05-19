$(document).ready(function() {
    var dataAdapter = new $.jqx.dataAdapter({
        datatype: 'json',
        datafields: [
            {name: 'date_entered'},
            {name: 'user_entered'},
            {name: 'output'},
            {name: 'amount'},
            {name: 'notes'}
        ],
        url: '?r=activity/listMovements',
        type: 'POST',
        data: {
            id: $("[id^=log]").attr("id").split("-")[1]
        }
    });

    $("[id^=log]").jqxDataTable({
        source: dataAdapter,
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Entered by</span>', dataField: 'user_entered', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Output</span>', dataField: 'output', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Spent Amount</span>', dataField: 'amount', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Notes</span>', dataField: 'notes', width: '40%'}
        ],
        groups: ['date_entered'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";
        },
        width: '100%',
        pageable: true,
        sortable: true
    });

    $("#refresh").click(function() {
        $("[id^=log]").jqxDataTable('updatebounddata');
    });
});