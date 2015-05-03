$(document).ready(function() {
    $("#leadmeasure-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'},
                {name: 'status'},
                {name: 'actions'}
            ],
            url: '?r=leadMeasure/listEntry',
            type: 'POST',
            data: {
                ubt: $("#ubt").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure</span>', dataField: 'description', width: '60%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'actions', width: '20%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
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

    $("#target-input").jqxNumberInput({
        theme: 'office',
        height: '35px',
        textAlign: 'left',
        digits: 12,
        symbol: ''
    }).on("valuechanged", function(event) {
        console.log($("#target-input").val());
        $("[name*=targetFigure]").val($("#target-input").val());
    });

    if ($("#target").val() !== '') {
        $("#target-input").val($("[name*=targetFigure]").val());
    }

    $("#uom-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=uom/listUnitofMeasures'
        }),
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        enableBrowserBoundsDetection: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on('select', function(event) {
        if (event.args) {
            $("#uom").val(event.args.item.value);
        }
    }).on('bindingComplete', function() {
        if ($("#uom").val() !== '') {
            console.log($("#uom").val());
            $("#uom-input").val($("#uom").val());
        }
    });

    var startDate = $("#ubt-start").val().split('-');
    var endDate = $("#ubt-end").val().split('-');

    $("#timeline-input").jqxDateTimeInput({
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
        var dates = $("#timeline-input").jqxDateTimeInput('getRange');

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

        $("#lm-start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-1");
        $("#lm-end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
    }).jqxDateTimeInput('setRange', $("#lm-start").val(), $("#lm-end").val());

    $('#lm-status').jqxWindow({
        title: '<strong>Confirm Update of Lead Measure Status</strong>',
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")
    });

    $("#deny").click(function() {
        $("#leadmeasure-list").jqxDataTable('updateBoundData');
    });

    $("[id^=accept]").click(function() {
        var data = $(this).attr('id').split("-");
        var id = data[1];
        var status = data[2];

        $.post("?r=leadMeasure/updateStatus",
                {
                    "LeadMeasure":{
                        'id': id,
                        'leadMeasureEnvironmentStatus': status
                    }
                },
        function(data) {
            var response = $.parseJSON(data);
            window.location = response.url;
        });
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=leadMeasure/validateInput",
            data: {
                "LeadMeasure": {
                    "description": $("[name*=description]").val(),
                    "designation": $("[name*=designation]").val(),
                    "leadMeasureEnvironmentStatus": $("[name*=leadMeasureEnvironmentStatus]").val(),
                    "targetFigure": $("[name*=targetFigure]").val(),
                    "startingPeriod": $("#lm-start").val(),
                    "endingPeriod": $("#lm-end").val()
                },
                "UnitOfMeasure": {
                    "id": $("#uom").val()
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