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
});