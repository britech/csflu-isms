$(document).ready(function() {
    $("#leadmeasure-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'},
                {name: 'status'},
                {name: 'actions'}
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
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Unit Breakthrough</span>', dataField: 'description', width: '60%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Status</span>', dataField: 'status', width: '20%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'actions', width: '20%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        sortable: true,
        selectionMode: 'singleRow'
    }).on("rowClick", function() {
        $("[id^=disable]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text-status").html("Do you want <strong>DEACTIVATE</strong> the Lead Measure, <strong>" + text + "</strong>, from the Unit Breakthrough?");
            $("#lm-status").jqxWindow('open');
            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1] + "-I");
        });

        $("[id^=enable]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text-status").html("Do you want <strong>ACTIVATE</strong> the Lead Measure, <strong>" + text + "</strong>, from the Unit Breakthrough?");
            $("#lm-status").jqxWindow('open');
            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1] + "-A");
        });
    });

    if ($("[name*=validationMode]").val() === '1') {
        $("[name*=description]").tagEditor({
            delimiter: '+;',
            maxLength: -1,
            forceLowercase: false
        });
    }

    $('#lm-status').jqxWindow({
        title: '<strong>Confirm Update of Lead Measure Status</strong>',
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")
    });

    $("#deny").click(function() {
        $("#leadmeasure-list").jqxDataTable('updateBoundData');
    });

    $("[id^=accept]").click(function() {
        var data = $(this).attr('id').split("-");
        var id = data[1];
        var status = data[2];
        
        $.post("?r=ubt/updateLeadMeasureStatus",
                {lm: id, status: status},
                function(data) {
                    var response = $.parseJSON(data);
                    window.location = response.url;
                });
    });

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