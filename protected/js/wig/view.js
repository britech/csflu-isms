$(document).ready(function() {
    $('#timeline-prompt').jqxWindow({
        title: '<strong>Update Timeline</strong>',
        width: 500,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none'
    });

    $("[id^=update]").click(function() {
        $("#timeline-prompt").jqxWindow('open');
    });

    var startDate = $("#ubt-start").val().split('-');
    var endDate = $("#ubt-end").val().split('-');

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

        $("#wig-start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-" + startingDate.getDate());
        $("#wig-end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + endingDate.getDate());
    }).jqxDateTimeInput('setRange', $("#wig-start").val(), $("#wig-end").val());
});