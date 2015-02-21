$(document).ready(function() {
    $("#leadmeasure-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'},
                {name: 'status'}
            ],
            url: '?r=ubt/listLeadMeasures',
            type: 'POST',
            data: {
                ubt: $("#ubt").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Unit Breakthrough</span>', dataField: 'description', width: '80%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', width: '20%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
    });

    if ($("[name*=validationMode]").val() === '1') {
        $("[name*=description]").tagEditor({
            delimiter: '+;',
            maxLength: -1,
            forceLowercase: false
        });
    }

    $("#validation-container").hide();

    $(".ink-form").submit(function() {
        var input = $("[name*=description]").val();
        if (input === '') {
            $("#validation-container").show();
            $("#validation-content").html("Lead Measures should be defined");
            return false;
        } else {
            var data = input.split("+");
            if (data.length > 2) {
                $("#validation-container").show();
                $("#validation-content").html("Lead Measures should be defined");
                return false;
            } else {
                return true;
            }
        }
    });
});