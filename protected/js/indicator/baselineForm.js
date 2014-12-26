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
    }).on("rowClick", function() {
        $("[id^=remove]").click(function() {
            var text = $(this).parent().siblings("td + td").html();
            $("#text").html("Delete baseline data with value of <strong>" + text + "</strong> in this Indicator.")
            $("#delete-baseline").jqxWindow('open');
            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
        });

        $("[id^=view]").click(function() {
            var id = $(this).attr('id').split("-")[1];
            $("#about-baseline").jqxWindow('open');
            $.post("?r=indicator/getBaseline",
                    {id: id},
            function(data) {
                var response = $.parseJSON(data);
                $("#yearCovered").html(response.coveredYear);
                $("#figureValue").html(response.figureValue);
                $("#others").html(response.notes);
            });
        });
    });

    $('#delete-baseline').jqxWindow({
        title: '<strong>Confirm Baseline Data Deletion</strong>',
        width: 300,
        height: 130,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")
    }).on("close", function() {
        $("#baselineTable").jqxDataTable('updateBoundData');
    });

    $("#deny").click(function() {
        $("#baselineTable").jqxDataTable('updateBoundData');
    });

    $("[id^=accept]").click(function() {
        var id = $(this).attr('id').split('-')[1];
        $.post("?r=indicator/deleteBaseline",
                {id: id},
        function(data) {
            var response = $.parseJSON(data);
            window.location = response.url;
        });
    });

    $('#about-baseline').jqxWindow({
        width: 500,
        height: 300,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")
    }).on("close", function() {
        $("#baselineTable").jqxDataTable('updateBoundData');
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