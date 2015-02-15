$(document).ready(function() {
    $("#component-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'description'}
            ],
            url: '?r=project/listComponents',
            type: 'POST',
            data: {
                initiative: $("#initiative").val()
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
            $("#component").val(event.args.item.value);
        }
    }).on("bindingComplete", function() {
        $("#component-input").val($("#component").val());
    });
    
    $("[name*=owners]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    var startDate = $("#initiative-start").val().split('-');
    var endDate = $("#initiative-end").val().split('-');

    $("#timeline").jqxDateTimeInput({
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
        var dates = $("#timeline").jqxDateTimeInput('getRange');

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

        $("#activity-start").val(startingDate.getFullYear() + "-" + (startingDate.getMonth() + 1) + "-1");
        $("#activity-end").val(endingDate.getFullYear() + "-" + (endingDate.getMonth() + 1) + "-" + lastDayOfEndingDate);
    }).jqxDateTimeInput('setRange', $("#activity-start").val(), $("#activity-end").val());
    
    $("#budgetAmount-input").jqxNumberInput({ 
        theme: 'office',
        height: '35px',
        textAlign: 'left',
        digits: 12,
        symbol: 'PhP'
    }).on("valuechanged", function(event) {
        console.log($("#budgetAmount-input").val());
        $("#budgetAmount").val($("#budgetAmount-input").val());
    });
    
    if($("#budgetAmount").val() !== ''){
        $("#budgetAmount-input").val($("#budgetAmount").val());
    }
    
    $("#activity-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'phase'},
                {name: 'component'},
                {name: 'activityId'},
                {name: 'activity'},
                {name: 'actions'}
            ],
            url: '?r=project/listActivities',
            type: 'POST',
            data: {
                initiative: $("#initiative").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '', dataField: 'activityId', width: '10%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Activity</span>', dataField: 'activity', width: '70%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'actions', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: '100',
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['phase', 'component'],
        groupsRenderer: function(value, rowData, level) {
            if(level === 0){
                return "<strong style=\"font-size: 12px;\">" + value + "</strong>";
            } else if(level === 1){
                return "<strong style=\"margin-left: 5%; font-size: 11px;\">" + value + "</strong>";
            }
        }
    }).on("rowClick", function() {
//        $("[id^=remove]").click(function() {
//            var text = $(this).parent().siblings("td").html();
//            $("#text").html("Do you want to delete the component, <strong>" + text + "</strong>, in the Initiative");
//            $("#delete-component").jqxWindow('open');
//            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
//
//            var data = $(this).attr('id').split('-');
//            componentId = data[1];
//            phaseId = data[2];
//        });
    });

    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=project/validateActivityInput",
            data: {
                "Activity": {
                    'title': $("[name*=title]").val(),
                    'descriptionOfTarget': $("[name*=descriptionOfTarget]").val(),
                    'indicator': $("[name*=indicator]").val(),
                    'targetFigure': $("[name*=targetFigure]").val(),
                    'budgetAmount': $("#budgetAmount").val(),
                    'sourceOfBudget': $("[name*=sourceOfBudget]").val(),
                    'owners': $("[name*=owners]").val(),
                    'startingPeriod': $("#activity-start").val(),
                    'endingPeriod': $("#activity-end").val()
                },
                "Component": {
                    "id": $("#component").val()
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