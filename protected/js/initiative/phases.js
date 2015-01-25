$(document).ready(function() {
    $("#phaseNumber-input").jqxNumberInput({
        inputMode: 'simple',
        spinButtons: true,
        min: 1,
        max: 100,
        decimalDigits: 0,
        height: '35px',
        textAlign: 'left',
        width: '150px'
    });

    $("#phaseNumber-input").on('valuechanged', function(event) {
        $("[name*=phaseNumber]").val(event.args.value);
    });
    
    $("#components").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });
});