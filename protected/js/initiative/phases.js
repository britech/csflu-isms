$(document).ready(function() {

    if ($("[name*=validationMode]").val() === '1') {
        $("#phaseNumber-input").jqxNumberInput({
            inputMode: 'simple',
            spinButtons: true,
            min: 1,
            max: 100,
            decimalDigits: 0,
            height: '35px',
            textAlign: 'left',
            width: '150px'
        }).on("valuechanged", function(event) {
            $("[name*=phaseNumber]").val(event.args.value);
        });
    }

    $("#phase-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'phaseNumber'},
                {name: 'title'},
                {name: 'actions'}
            ],
            url: '?r=project/listPhases',
            type: 'POST',
            data: {
                initiative: $("#initiative").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">#</span>', dataField: 'phaseNumber', width: '5%', cellsAlign: 'center'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Phase</span>', dataField: 'title', width: '75%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Action</span>', dataField: 'actions', width: '20%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow'
    }).on("rowClick", function() {
//        $("[id^=remove]").click(function() {
//            var text = $(this).parent().siblings("td").html();
//            $("#text").html("Do you want to delete this Implementing Office? Continuing will remove the Implementing Office, <strong>" + text + "</strong>, from the Initiative");
//            $("#delete-implem").jqxWindow('open');
//            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
//        });
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=project/validatePhaseInput",
            data: {
                "Phase": {
                    'phaseNumber': $("[name*=phaseNumber]").val(),
                    'title': $("[name*=title]").val(),
                    'description': $("[name*=description]").val()
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