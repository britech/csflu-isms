$(document).ready(function() {
    $("#leadMeasures").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    var startDate = $("#map-start").val().split('-');
    var endDate = $("#map-end").val().split('-');

    $("#timeline-input").jqxDateTimeInput({
        min: new Date(startDate[0], startDate[1] - 1, startDate[2]),
        max: new Date(endDate[0], endDate[1] - 1, endDate[2]),
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
        var dates = $("#timeline-input").jqxDateTimeInput('getRange');

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

        $("#ubt-start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-1");
        $("#ubt-end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
    });

    if ($("#ubt-start").val() !== '' && $("#ubt-end").val() !== '') {
        $("#timeline-input").jqxDateTimeInput('setRange', $("#start").val(), $("#end").val());
    }

    $("#objectives-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'objective'}
            ],
            url: '?r=objective/listObjectives',
            type: 'POST',
            data: {
                map: $("#map").val()
            }
        }),
        valueMember: 'id',
        displayMember: 'objective',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none',
        enableBrowserBoundsDetection: true,
        multiSelect: true
    }).on('change', function() {
        var input = [];
        var items = $("#objectives-input").jqxComboBox('getSelectedItems');
        var i = 0;
        $.each(items, function() {
            input[i] = this.value;
            i++;
        });
        $("#objectives").val(input.join("/"));
    });

    $("#measures-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'indicator'}
            ],
            url: '?r=scorecard/listLeadMeasures',
            type: 'POST',
            data: {
                map: $("#map").val(),
                readonly: 0
            }
        }),
        valueMember: 'id',
        displayMember: 'indicator',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none',
        enableBrowserBoundsDetection: true,
        multiSelect: true
    }).on('change', function() {
        var input = [];
        var items = $("#measures-input").jqxComboBox('getSelectedItems');
        var i = 0;
        $.each(items, function() {
            input[i] = this.value;
            i++;
        });
        $("#measures").val(input.join("/"));
    });

    $("#department-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=department/listDepartments'
        }),
        selectedIndex: 0,
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none',
        enableBrowserBoundsDetection: true
    }).on('select', function(event) {
        if (event.args) {
            $("#department").val(event.args.item.value);
        }
    });
    
    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=ubt/validateUbtInput",
            data: {
                "UnitBreakthrough": {
                    'description': $("[name*=description]").val(),
                    'startingPeriod': $("#ubt-start").val(),
                    'endingPeriod': $("#ubt-end").val(),
                    'validationMode': $("[name*=validationMode]").val()
                },
                "LeadMeasure": {
                    'description': $("#leadMeasures").val()
                },
                "Objective": {
                    "id": $("#objectives").val()
                },
                "MeasureProfile": {
                    "id": $("#measures").val()
                },
                "Department": {
                    "id": $("#department").val()
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