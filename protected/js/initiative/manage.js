$(document).ready(function() {
    $("[id^=initiativeList]").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id', type: 'string'},
                {name: 'description'},
                {name: 'status'},
                {name: 'map'},
                {name: 'action'}
            ],
            url: '?r=initiative/listInitiativesByImplementingOffice',
            type: 'POST',
            data: {
                department: $("[id^=initiativeList]").attr('id').split("-")[1]
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Initiative</span>', dataField: 'description', width: '80%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', cellsAlign: 'center', width: '10%'},
            {dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['map'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";
        }
    }).on('rowClick', function(event) {
        var args = event.args;
        var id = args.row.id;
        $("#initiative").val(id);
        $("#timeline-container").jqxWindow('open');
    });

    $("#refresh").click(function() {
        $("[id^=initiativeList]").jqxDataTable('updateBoundData');
    });

    $('#timeline-container').jqxWindow({
        title: '<strong>Select Month and Year</strong>',
        width: 400,
        height: 180,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none'
    });

    $("#timeline-input").jqxDateTimeInput({
        min: new Date(2000, 1, 1),
        max: new Date(2100, 12, 31),
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

        var lastDayOfEndingDate = 0;

        switch (date.getMonth()) {
            case 0:
            case 2:
            case 4:
            case 6:
            case 7:
            case 9:
            case 11:
                lastDayOfEndingDate = 31;
                break;

            case 3:
            case 5:
            case 8:
            case 10:
                lastDayOfEndingDate = 30;
                break;

            case 1:
                if (date.getFullYear() % 4 === 0) {
                    lastDayOfEndingDate = 29;
                } else {
                    lastDayOfEndingDate = 28;
                }
                break;
        }

        $("[name*=startingPeriod]").val(date.getFullYear() + "-" + (date.getMonth() + 1) + "-1");
        $("[name*=endingPeriod]").val(date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + lastDayOfEndingDate);
    });
});