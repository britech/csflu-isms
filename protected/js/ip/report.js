$(document).ready(function() {
    $("#timeline-input").jqxDateTimeInput({
        min: new Date(2000, 1, 1),
        formatString: 'MMMM-dd-yyyy',
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

        $("[name*=startingPeriod]").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-" + startingDate.getDate());
        $("[name*=endingPeriod]").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + endingDate.getDate());
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=ip/validateReportInput",
            data: {
                "IpReportInput": {
                    'startingPeriod': $("[name*=startingPeriod]").val(),
                    'endingPeriod': $("[name*=endingPeriod]").val()
                },
                "UserAccount": {
                    'id': $("#user").val()
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