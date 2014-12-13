$(document).ready(function() {

    var startingDateData = $("#map-start").val().split("-");
    var endingDateData = $("#map-end").val().split("-");

    $("#periods").jqxDateTimeInput({
        min: new Date(startingDateData[0], startingDateData[1] - 1, startingDateData[2]),
        max: new Date(endingDateData[0], endingDateData[1] - 1, endingDateData[2]),
        formatString: 'MMMM-yyyy',
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
        var dates = $("#periods").jqxDateTimeInput('getRange');

        var startingDate = dates.from;
        var endingDate = dates.to;

        var lastDayOfEndingDate = 0;

        switch (endingDate.getMonth()) {
            case 0:
            case 2:
            case 4:
            case 6:
            case 7:
            case 9:
            case 11:
                lastDayOfEndingDate = 31;
                break;

            case 3:
            case 5:
            case 8:
            case 10:
                lastDayOfEndingDate = 30;
                break;

            case 1:
                if (endingDate.getFullYear() % 4 === 0) {
                    lastDayOfEndingDate = 29;
                } else {
                    lastDayOfEndingDate = 28;
                }
                break;
        }

        $("#obj-start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-" + startingDate.getDate());
        $("#obj-end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
    });

    $("#periods").jqxDateTimeInput('setRange', $("#map-start").val(), $("#map-end").val());
    if (!($("#obj-start").val() !== '' && $("#obj-start").val() !== '')) {
        $("#obj-start").val($("#map-start").val());
        $("#obj-end").val($("#map-end").val());
    }

    $("#objectives").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'},
                {name: 'perspective'},
                {name: 'theme'},
                {name: 'actions'}
            ],
            url: '?r=objective/renderTable',
            type: 'POST',
            data: {
                map: $("#map-id").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Objective</span>', dataField: 'description', width: '80%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'actions', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: '20',
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['perspective', 'theme'],
        groupsRenderer: function(value, rowData, level) {
            if (level === 0) {
                return "<strong>" + value + "</strong>";
            } else if (level === 1 && value !== "--") {
                return "<strong style=\"margin-left: 18px;\">" + value + "</strong>";
            } else if (level === 1 && value === "--") {
                return "";
            }
        }
    }).on("rowClick", function() {
        $("[id^=remove]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text").html("Do you want to delete this objective? Continuing will remove the objective, <strong>" + text + "</strong>, in the Strategy Map")
            $("#delete-objective").jqxWindow('open');
            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
        });
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=objective/validate",
            data: {"Objective": {
                    'description': $("[name*=description]").val(),
                    'startingPeriodDate': $("#obj-start").val(),
                    'endingPeriodDate': $("#obj-end").val()
                },
                "Perspective": {
                    'id': $("#pers-id").val()
                },
                "Theme": {
                    'id': $("#theme-id").val()
                },
                "mode": 1},
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

    $("#description-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'}
            ],
            url: '?r=objective/listAll',
            type: 'POST'
        }),
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("[name*=description]").val(event.args.item.value);
        }
    }).on("bindingComplete", function() {
        $("#description-input").val($("#description").val());
    });

    $("#description-input").change(function() {
        console.log($(this).val());
        $("[name*=description]").val($(this).val());
    });

    $('#delete-objective').jqxWindow({
        title: '<strong>Confirm Objective Deletion</strong>',
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
        $("#objectives").jqxDataTable('updateBoundData');
    });


    $("[id^=accept]").click(function() {
        var id = $(this).attr('id').split('-')[1];
        $.post("?r=objective/delete",
                {id: id},
        function(data) {
            var response = $.parseJSON(data);
            window.location = response.url;
        });
    });
});


