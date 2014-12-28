$(document).ready(function() {
    $("#profileList").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'perspective'},
                {name: 'objective'},
                {name: 'indicator'},
                {name: 'action'}
            ],
            url: '?r=scorecard/listLeadMeasures',
            type: 'POST',
            data: {
                map: $("#map").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Measure</span>', dataField: 'indicator', width: '90%'},
            {text: '', dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['perspective', 'objective'],
        groupsRenderer: function(value, rowData, level) {
            if (level === 0) {
                return "<strong>" + value + "</strong>";
            } else if (level === 1) {
                return "<strong style=\"margin-left: 20px;\">" + value + "</strong>";
            }
        }
    });

    $("#objective-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'description'}
            ],
            url: '?r=objective/listObjectives',
            type: 'POST',
            data: {
                map: $("#map").val()
            }
        }),
        valueMember: 'id',
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("#objective").val(event.args.item.value);
        }
    }).on("bindingComplete", function() {
        //$("#description-input").val($("#description").val());
    });

    $("#indicator-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'description'}
            ],
            url: '?r=indicator/listIndicators'
        }),
        valueMember: 'id',
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("#indicator").val(event.args.item.value);
        }
    }).on("bindingComplete", function() {
        //$("#description-input").val($("#description").val());
    });

    $(".ink-form").submit(function() {
        var result = false;

        var frequencyValues = [];
        frequencyValues.length = $("[name*=frequencyOfMeasure] :checked").length;
        var i = 0;
        $("[name*=frequencyOfMeasure]").each(function() {
            if ($(this).is(":checked")) {
                console.log($(this).val());
                frequencyValues[i] = $(this).val();
                i++;
            }
        });

        $.ajax({
            type: "POST",
            url: "?r=measure/validateInput",
            data: {"MeasureProfile": {
                    'frequencyOfMeasure': frequencyValues,
                    'measureType': $("[name*=measureType]").val(),
                    'measureProfileEnvironmentStatus': $("[name*=measureProfileEnvironmentStatus]").val()
                },
                "Objective": {
                    'id': $("#objective").val()
                },
                "Indicator": {
                    'id': $("#indicator").val()
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