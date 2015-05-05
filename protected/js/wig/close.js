$(document).ready(function() {

    $("[name*=notes]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    var startDate = $("#ubt-start").val().split('-');
    var endDate = $("#ubt-end").val().split('-');

    $("#meeting-date-input").jqxDateTimeInput({
        min: new Date(startDate[0], startDate[1] - 1, startDate[2]),
        max: new Date(endDate[0], endDate[1] - 1, endDate[2]),
        formatString: 'MMMM dd, yyyy',
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
        var dateString = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
        $("[name*=meetingDate]").val(dateString);
    });

    $("#timeline-input").jqxDateTimeInput({
        min: new Date(startDate[0], startDate[1] - 1, startDate[2]),
        max: new Date(endDate[0], endDate[1] - 1, endDate[2]),
        formatString: 'MMMM dd, yyyy',
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

        $("[name*=actualSessionStartDate]").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-" + startingDate.getDate());
        $("[name*=actualSessionEndDate]").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + endingDate.getDate());
    });

    $("#meeting-time-start-input, #meeting-time-end-input").jqxDateTimeInput({
        width: '100%',
        height: '40px',
        formatString: 't',
        showCalendarButton: false,
        theme: 'office'
    }).on('change', function(event) {
        var source = event.target.id;
        var date = event.args.date;

        var minutes = date.getMinutes().toString().length === 1 ? "0" + date.getMinutes() : date.getMinutes();
        var hours = date.getHours().toString().length === 1 ? "0" + date.getHours() : date.getHours();


        var timeString = hours + ":" + minutes + ":00";

        if (source === 'meeting-time-start-input') {
            $("[name*=meetingTimeStart]").val(timeString);
        } else if (source === 'meeting-time-end-input') {
            $("[name*=meetingTimeEnd]").val(timeString);
        }
    });

    $("#ubt-input").jqxNumberInput({
        theme: 'office',
        height: '40px',
        textAlign: 'left',
        digits: 12,
        symbol: $("#uom-ubt").val(),
        symbolPosition: 'right'
    }).on("valuechanged", function() {
        $("[name*=ubtFigure]").val($("#ubt-input").val());
    });

    $("#lm1-input").jqxNumberInput({
        theme: 'office',
        height: '40px',
        textAlign: 'left',
        digits: 12,
        symbol: $("#uom-lm1").val(),
        symbolPosition: 'right'
    }).on("valuechanged", function(event) {
        console.log($("#baseline-input").val());
        $("[name*=firstLeadMeasureFigure]").val($("#lm1-input").val());
    });

    $("#lm2-input").jqxNumberInput({
        theme: 'office',
        height: '40px',
        textAlign: 'left',
        digits: 12,
        symbol: $("#uom-lm2").val(),
        symbolPosition: 'right'
    }).on("valuechanged", function(event) {
        console.log($("#baseline-input").val());
        $("[name*=secondLeadMeasureFigure]").val($("#lm2-input").val());
    });

    $("#validation-container").hide();

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=wig/validateWigClosureInput",
            data: {
                "WigMeeting": {
                    'actualSessionStartDate': $("[name*=actualSessionStartDate]").val(),
                    'actualSessionEndDate': $("[name*=actualSessionEndDate]").val(),
                    'meetingVenue': $("[name*=meetingVenue]").val(),
                    'meetingDate': $("[name*=meetingDate]").val(),
                    'meetingTimeStart': $("[name*=meetingTimeStart]").val(),
                    'meetingTimeEnd': $("[name*=meetingTimeEnd]").val()
                },
                "UnitBreakthroughMovement": {
                    'ubtFigure': $("[name*=ubtFigure]").val(),
                    'firstLeadMeasureFigure': $("[name*=firstLeadMeasureFigure]").val(),
                    'secondLeadMeasureFigure': $("[name*=secondLeadMeasureFigure]").val(),
                    'notes': $("[name*=notes]").val()
                }
            },
            async: false,
            success: function(data) {
                try {
                    response = $.parseJSON(data);
                    result = response.respCode === '00';
                    if (!result) {
                        $("#validation-container").show();
                        $("#validation-message").html(response.message);
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        });

        return result;
    });

});