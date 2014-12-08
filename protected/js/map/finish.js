$(document).ready(function() {
    var startingDateData = $("[name*=startingPeriodDate]").val().split("-");
    var endingDateData = $("[name*=endingPeriodDate]").val().split("-");

    $("#implem-date-input, #term-date-input").jqxDateTimeInput({
        min: new Date(startingDateData[0], startingDateData[1] - 1, startingDateData[2]),
        max: new Date(endingDateData[0], endingDateData[1] - 1, endingDateData[2]),
        formatString: 'MMMM dd, yyyy',
        readonly: true,
        allowKeyboardDelete: false,
        allowNullDate: false,
        width: '100%',
        height: '40px',
        theme: 'office',
        enableBrowserBoundsDetection: true,
        animationType: 'none'
    }).on('change', function() {
        var id = $(this).attr('id');
        var dateInput = $(this).jqxDateTimeInput("getDate");
        var displayDate = dateInput.getFullYear() + "-" + (dateInput.getMonth() + 1) + "-" + dateInput.getDate()
        console.log(id);

        if (id === 'implem-date-input') {
            $("[name*=implementationDate]").val(displayDate);
        }

        if (id === 'term-date-input') {
            $("[name*=terminationDate]").val(displayDate);
        }
    });


    if ($("[name*=implementationDate]").val() !== '') {
        $("#implem-date-input").jqxDateTimeInput('setDate', $("[name*=implementationDate]").val());
    }

    if ($("[name*=terminationDate]").val() !== '') {
        $("#term-date-input").jqxDateTimeInput('setDate', $("[name*=terminationDate]").val());
    }

    $("#implem-date, #term-date").hide();
    resolveInputFields($("[name*=strategyEnvironmentStatus]").val());


    $("[name*=strategyEnvironmentStatus]").click(function() {
        var selectedValue = $(this).val();
        $("#implem-date, #term-date").hide();
        resolveInputFields(selectedValue);
    });

    function resolveInputFields(status) {
        if (status === 'A') {
            $("#implem-date").show();
        } else if (status === 'D') {
            $("#implem-date, #term-date").hide();
        } else {
            $("#implem-date, #term-date").show();
            var label = "";
            switch (status) {
                case "I":
                    label = "Date Deactivated&nbsp*";
                    break;

                case "C":
                    label = "Date Completed&nbsp*";
                    break;

                case "T":
                    label = "Date Terminated&nbsp*";
                    break;
            }
            $("#term-label").html(label);
        }
    }
});