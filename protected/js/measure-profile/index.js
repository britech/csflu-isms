$(document).ready(function() {
    $("#profileList").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'perspective'},
                {name: 'objective'},
                {name: 'indicator'},
                {name: 'action'}
            ],
            url: '?r=scorecard/listLeadMeasures',
            type: 'POST',
            data: {
                map: $("#map").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure</span>', dataField: 'indicator', width: '90%'},
            {text: '', dataField: 'action', width: '10%', cellsAlign: 'center'}
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
    });

    $("#objective-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'description'}
            ],
            url: '?r=objective/listObjectives',
            type: 'POST',
            data: {
                map: $("#map").val()
            }
        }),
        valueMember: 'id',
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("#objective").val(event.args.item.value);
            $.post("?r=objective/get",
                    {id: event.args.item.value},
            function(response) {
                data = $.parseJSON(response);
                $("[name*=timelineStart]").val(data.startingPeriodDate);
                $("[name*=timelineEnd]").val(data.endingPeriodDate);
                
                var startDate = data.startingPeriodDate.split('-');
                var endDate = data.endingPeriodDate.split('-');
                
                $("#periods").jqxDateTimeInput('setMinDate', new Date(startDate[0], startDate[1] - 1, startDate[2]));
                $("#periods").jqxDateTimeInput('setMaxDate', new Date(endDate[0], endDate[1] - 1, endDate[2]));
                
                $("#periods").jqxDateTimeInput('setRange', $("[name*=timelineStart]").val(), $("[name*=timelineEnd]").val());
            });
        }
    }).on("bindingComplete", function() {
        //$("#description-input").val($("#description").val());
    });

    var startDate = $("#mapStart").val().split('-');
    var endDate = $("#mapEnd").val().split('-');

    $("#periods").jqxDateTimeInput({
        min: new Date(startDate[0], startDate[1] - 1, startDate[2]),
        max: new Date(endDate[0], endDate[1] - 1, endDate[2]),
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

        $("[name*=timelineStart]").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-" + startingDate.getDate());
        $("[name*=timelineEnd]").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
    });

    $("#periods").jqxDateTimeInput('setRange', $("[name*=timelineStart]").val(), $("[name*=timelineEnd]").val());

    $("#indicator-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'description'}
            ],
            url: '?r=indicator/listIndicators'
        }),
        valueMember: 'id',
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("#indicator").val(event.args.item.value);
        }
    }).on("bindingComplete", function() {
        //$("#description-input").val($("#description").val());
    });

    $(".ink-form").submit(function() {
        var result = false;

        var frequencyValues = [];
        frequencyValues.length = $("[name*=frequencyOfMeasure] :checked").length;
        var i = 0;
        $("[name*=frequencyOfMeasure]").each(function() {
            if ($(this).is(":checked")) {
                console.log($(this).val());
                frequencyValues[i] = $(this).val();
                i++;
            }
        });

        $.ajax({
            type: "POST",
            url: "?r=measure/validateInput",
            data: {"MeasureProfile": {
                    'frequencyOfMeasure': frequencyValues,
                    'measureType': $("[name*=measureType]").val(),
                    'measureProfileEnvironmentStatus': $("[name*=measureProfileEnvironmentStatus]").val()
                },
                "Objective": {
                    'id': $("#objective").val()
                },
                "Indicator": {
                    'id': $("#indicator").val()
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