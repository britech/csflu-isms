$(document).ready(function() {
    $("[name*=beneficiaries], [name*=advisers]").tagEditor({
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

        $("#start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-1");
        $("#end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
    }).jqxDateTimeInput('setRange', $("#start").val(), $("#end").val());

    if ($("[name*=validationMode]").val() === "1") {
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

        $("#offices-input").jqxComboBox({
            source: new $.jqx.dataAdapter({
                datatype: 'json',
                datafields: [
                    {name: 'id'},
                    {name: 'name'}
                ],
                url: '?r=department/listDepartments'
            }),
            valueMember: 'id',
            displayMember: 'name',
            width: '100%',
            searchMode: 'containsignorecase',
            autoComplete: true,
            theme: 'office',
            height: '35px',
            animationType: 'none',
            multiSelect: true
        }).on('change', function() {
            var input = [];
            var items = $("#offices-input").jqxComboBox('getSelectedItems');
            var i = 0;
            $.each(items, function() {
                input[i] = this.value;
                i++;
            });
            $("#offices").val(input.join("/"));
        });
    }
});