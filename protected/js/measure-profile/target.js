$(document).ready(function() {
    if ($("[name*=validationMode]").val() === "1") {
        var minYear = parseInt($("#start-pointer").val()) + 1;
        $("#year-input").jqxNumberInput({
            inputMode: 'advanced',
            spinButtons: true,
            min: minYear,
            max: 2100,
            decimalDigits: 0,
            height: '35px',
            textAlign: 'left',
            theme: 'office',
            digits: 4,
            groupSeparator: ''
        });

        $("#year-input").on('valuechanged', function(event) {
            $("#year").val(event.args.value);
        });
    }

    $("#target-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'year'},
                {name: 'group'},
                {name: 'figure'},
                {name: 'action'}
            ],
            url: '?r=measure/listTargets',
            type: 'POST',
            data: {
                profile: $("#profile-id").val()
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
//        $("[id^=remove]").click(function() {
//            var text = $(this).parent().siblings("td + td").html();
//            $("#text").html("Delete baseline data with value of <strong>" + text + "</strong> in this Indicator.")
//            $("#delete-baseline").jqxWindow('open');
//            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
//        });
//
//        $("[id^=view]").click(function() {
//            var id = $(this).attr('id').split("-")[1];
//            $("#about-baseline").jqxWindow('open');
//            $.post("?r=indicator/getBaseline",
//                    {id: id},
//            function(data) {
//                var response = $.parseJSON(data);
//                $("#yearCovered").html(response.coveredYear);
//                $("#figureValue").html(response.figureValue);
//                $("#others").html(response.notes);
//            });
//        });
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=measure/validateTargetInput",
            data: {"Target": {
                    'coveredYear': $("#year").val(),
                    'value': $("[name*=value]").val()
                }
            },
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