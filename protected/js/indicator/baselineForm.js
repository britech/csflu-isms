$(document).ready(function() {
    $("#baselineTable").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'year'},
                {name: 'group'},
                {name: 'figure'},
                {name: 'action'}
            ],
            url: '?r=indicator/listBaselines',
            type: 'POST',
            data: {
                id: $("#indicator-id").val(),
                action: 1
            }
        }),
        columnsresize: false,
        theme: 'office',
        groups: ['year'],
        groupsRenderer: function(value, rowData, level) {
            return "Year Covered:&nbsp;" + value;
        },
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Item</span>', dataField: 'group', width: '35%', cellsAlign: 'right'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Value</span>', dataField: 'figure'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;"></span>', dataField: 'action', cellsAlign: 'center', width: '25%'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 50
    });

    if ($("[name*=validationMode]").val() === "1") {
        $("#year").jqxNumberInput({
            inputMode: 'advanced',
            spinButtons: true,
            min: 2010,
            max: 2100,
            decimalDigits: 0,
            height: '35px',
            textAlign: 'left',
            theme: 'office',
            digits: 4,
            groupSeparator: ''
        });

        if ($("#yearValue").val() !== '') {
            $("#year").jqxNumberInput('val', $("#yearValue").val());
        }

        $("#year").on('valuechanged', function(event) {
            $("#yearValue").val(event.args.value);
        });
    }

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=indicator/validateBaselineEntry",
            data: {"Baseline": {
                    'coveredYear': $("[name*=coveredYear]").val(),
                    'value': $("[name*=value]").val()
                },
                "mode": $("[name*=validationMode]").val()},
            async: false,
            success: function(data) {
                try {
                    response = $.parseJSON(data);
                    result = response.respCode === '00';
                } catch (e) {
                    $("#validation-container").html(data);
                }
            }
        });

        return result;
    });
});