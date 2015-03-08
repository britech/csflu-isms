$(document).ready(function() {
    var startDate = $("#ubt-start").val().split('-');
    var endDate = $("#ubt-end").val().split('-');

    $("#timeline").jqxDateTimeInput({
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
        var dates = $("#timeline").jqxDateTimeInput('getRange');

        var startingDate = dates.from;
        var endingDate = dates.to;

        $("#wig-start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-" + startingDate.getDate());
        $("#wig-end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + endingDate.getDate());
    });

    if ($("#wig-start").val() !== '' && $("#wig-end").val() !== '') {
        $("#timeline").jqxDateTimeInput('setRange', $("#wig-start").val(), $("#wig-end").val());
    }

    $("#wig-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'number'},
                {name: 'timeline'},
                {name: 'status'},
                {name: 'action'}
            ],
            url: '?r=wig/listMeetings',
            type: 'POST',
            data: {
                ubt : $("#ubt").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">#</span>', dataField: 'number', width: '10%', cellsAlign:'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Timeline</span>', dataField: 'timeline', width: '40%', cellsAlign:'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', width: '20%', cellsAlign:'center'},
            {text: '', dataField: 'action', width: '30%', cellsAlign:'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow'
    });
});