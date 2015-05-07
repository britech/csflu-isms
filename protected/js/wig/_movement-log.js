$(document).ready(function() {
    $("[id^=log]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'date', type: 'string'},
                {name: 'ubt', type: 'string'},
                {name: 'lm1', type: 'string'},
                {name: 'lm2', type: 'string'},
                {name: 'notes', type: 'string'}
            ],
            url: '?r=wig/listUbtMovements',
            type: 'POST',
            data: {
                wig: $("[id^=log]").attr("id").split("-")[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Date Entered</span>', dataField: 'date', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">UBT Movement</span>', dataField: 'ubt', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure 1 Movement</span>', dataField: 'lm1', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure 2 Movement</span>', dataField: 'lm2', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Notes</span>', dataField: 'notes', width: '20%'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 10,
        sortable: true,
        selectionMode: 'singleRow'
    }).on("rowClick", function() {
        $("[id^=disable]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text-status").html("Do you want <strong>DEACTIVATE</strong> the Lead Measure, <strong>" + text + "</strong>, from the Unit Breakthrough?");
            $("#lm-status").jqxWindow('open');
            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1] + "-I");
        });

        $("[id^=enable]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text-status").html("Do you want <strong>ACTIVATE</strong> the Lead Measure, <strong>" + text + "</strong>, from the Unit Breakthrough?");
            $("#lm-status").jqxWindow('open');
            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1] + "-A");
        });
    });

    $("#refresh").click(function() {
        $("[id^=log]").jqxDataTable('updateBoundData');
    });
});