$(document).ready(function() {

    var startingDateData = $("#map-start").val().split("-");
    var endingDateData = $("#map-end").val().split("-");

    $("#periods").jqxDateTimeInput({
        min: new Date(startingDateData[0], startingDateData[1] - 1, startingDateData[2]),
        max: new Date(endingDateData[0], endingDateData[1] - 1, endingDateData[2]),
        formatString: 'MMMM-yyyy',
        selectionMode: 'range',
        readonly: true,
        allowKeyboardDelete: false,
        allowNullDate: false,
        width: '100%',
        height: '40px',
        theme: 'office',
        enableBrowserBoundsDetection: true,
        animationType: 'none'
    }).on('change', function() {
        var dates = $("#periods").jqxDateTimeInput('getRange');

        var startingDate = dates.from;
        var endingDate = dates.to;

        var lastDayOfEndingDate = 0;

        switch (endingDate.getMonth()) {
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
                if (endingDate.getFullYear() % 4 === 0) {
                    lastDayOfEndingDate = 29;
                } else {
                    lastDayOfEndingDate = 28;
                }
                break;
        }

        $("#obj-start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-" + startingDate.getDate());
        $("#obj-end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
    });

    $("#objectives").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'name'},
                {name: 'perspective'},
                {name: 'theme'}
            ],
            url: '?r=map/renderStrategyMapTable'
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Objective</span>', dataField: 'name'}
            
        ],
        width: '100%',
        pageable: true,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        groups: ['perspective', 'theme'],
        groupsRenderer: function(value, rowData, level) {
            if (level === 0) {
                return "<strong>" + value + "</strong>";
            } else {
                return "<strong style=\"margin-left: 20px;\">" + value + "</strong>";
            }
        }
    });
});


