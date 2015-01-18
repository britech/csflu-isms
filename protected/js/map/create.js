$(document).ready(function() {

    $("#periodDate").jqxDateTimeInput({
        min: new Date(2000, 1, 1),
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
        var dates = $("#periodDate").jqxDateTimeInput('getRange');

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

        $("[name*=startingPeriodDate]").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-1");
        $("[name*=endingPeriodDate]").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
        $("[name*=name]").val(endingDate.getFullYear() + " Development Strategy");
    });

    $("#mission, #values").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });


    var startingDate = $("[name*=startingPeriodDate]").val();
    var endingDate = $("[name*=endingPeriodDate]").val();

    if (startingDate !== '' && endingDate !== '') {
        $("#periodDate").jqxDateTimeInput('setRange', startingDate, endingDate);
    }

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=map/validateStrategyMap",
            data: {"StrategyMap": {
                    'visionStatement': $("[name*=visionStatement]").val(),
                    'missionStatement': $("[name*=missionStatement]").val(),
                    'valuesStatement': $("[name*=valuesStatement]").val(),
                    'strategyType': $("[name*=strategyType]").val(),
                    'startingPeriodDate': $("[name*=startingPeriodDate]").val(),
                    'endingPeriodDate': $("[name*=endingPeriodDate]").val(),
                    'name': $("[name*=endingPeriodDate]").val()}
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