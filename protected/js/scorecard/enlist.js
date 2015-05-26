$(document).ready(function() {
    $("[name*=notes]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    $("#value-input").jqxNumberInput({
        theme: 'office',
        height: '40px',
        textAlign: 'left',
        digits: 12,
        symbol: $("#uom").val(),
        symbolPosition: 'right'
    }).on("valuechanged", function() {
        $("[name*=movementValue]").val($("#value-input").val());
    });

    $(".ink-form").submit(function() {
        return false;
    });
});