$(document).ready(function() {
    $("[id^=profileList]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id', type: 'string'},
                {name: 'perspective'},
                {name: 'objective'},
                {name: 'indicator'},
                {name: 'action'}
            ],
            url: '?r=scorecard/listLeadMeasures',
            type: 'POST',
            data: {
                map: $("[id^=profileList]").attr('id').split("-")[1],
                readonly: 0
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure</span>', dataField: 'indicator', width: '75%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'action', width: '25%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['perspective', 'objective'],
        groupsRenderer: function(value, rowData, level) {
            if (level === 0) {
                return "<strong>" + value + "</strong>";
            } else if (level === 1) {
                return "<strong style=\"margin-left: 20px;\">" + value + "</strong>";
            }
        }
    }).on('rowClick', function(event) {
        var args = event.args;
        var id = args.row.id;
        $("[name=id]").val(id);
        $("#timeline-container").jqxWindow('open');
    });

    $("#refresh").click(function() {
        $("[id^=profileList]").jqxDataTable('updateBoundData');
    });

    $('#timeline-container').jqxWindow({
        title: '<strong>Select Month and Year</strong>',
        width: 500,
        height: 190,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none'
    });

    var startDate = $("[name=startingPeriod]").val().split('-');
    var endDate = $("[name=endingPeriod]").val().split('-');

    $("#timeline-input").jqxDateTimeInput({
        min: new Date(startDate[0], startDate[1] - 1, startDate[2]),
        max: new Date(endDate[0], endDate[1] - 1, endDate[2]),
        formatString: 'MMM yyyy',
        readonly: true,
        allowKeyboardDelete: false,
        allowNullDate: false,
        width: '100%',
        height: '40px',
        theme: 'office',
        enableBrowserBoundsDetection: true,
        animationType: 'none'
    }).on('change', function(event) {
        var date = event.args.date;
        $("[name=period]").val(date.getFullYear() + "-" + (date.getMonth() + 1));
    });
    
    $(".ink-form").submit(function() {
        var date = $("[name=period]").val();
        var id = $("[name=id]").val();
        if (date === '') {
            $("#tip").html("Coverage date is required.");
        } else {
            window.location = "?r=scorecard/movements&measure=" + id + "&period=" + date;
        }
        return false;
    });
});