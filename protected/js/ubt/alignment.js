$(document).ready(function() {
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

    $("#validation-container").hide();
    $(".ink-form").submit(function() {
        var objectives = $("#objectives").val();
        var measures = $("#measures").val();

        if (objectives === '' && measures === '') {
            $("#validation-container").show();
            $("#validation-header").html("Validation error. Please check your entries");
            $("#validation-content").html("An Objective or Measure should be selected");
            return false;
        }
    });
    
    $("#objectives-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'objective'},
                {name: 'action'}
            ],
            url: '?r=alignment/listUbtObjectiveAlignment',
            type: 'POST',
            data: {
                ubt: $("#ubt").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Objective</span>', dataField: 'objective', width: '90%'},
            {text: '', dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow'
    }).on("rowClick", function() {
        $("[id^=remove-objective]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text-objective").html("Do you want unlink this objective Continuing will remove the Objective, <strong>" + text + "</strong>, from the Unit Breakthrough");
            $("#delete-objective").jqxWindow('open');
            $("#accept-objective").prop('id', "accept-objective-" + $(this).attr('id').split('-')[2]);
        });
    });

    $('#delete-objective').jqxWindow({
        title: '<strong>Confirm Objective Unlinking</strong>',
        width: 300,
        height: 200,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny-objective")
    });

    $("#deny-objective").click(function() {
        $("#objectives-list").jqxDataTable('updateBoundData');
    });
    
    $("[id^=accept-objective]").click(function() {
        var id = $(this).attr('id').split('-')[2];
        var ubt = $("#ubt").val();
        $.post("?r=alignment/unlinkUbtObjectiveAlignment",
                {objective: id, 
                 ubt: ubt},
        function(data) {
            var response = $.parseJSON(data);
            window.location = response.url;
        });
    });

    $("#measures-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'indicator'},
                {name: 'action'}
            ],
            url: '?r=alignment/listUbtIndicatorAlignment',
            type: 'POST',
            data: {
                ubt: $("#ubt").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Indicator</span>', dataField: 'indicator', width: '90%'},
            {text: '', dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow'
    }).on("rowClick", function() {
        $("[id^=remove-measure]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text-measure").html("Do you want to unlink this Lead Measure? Continuing will remove the Lead Measure, <strong>" + text + "</strong>, from the Unit Breakthrough");
            $("#delete-measure").jqxWindow('open');
            $("#accept-measure").prop('id', "accept-measure-" + $(this).attr('id').split('-')[2]);
        });
    });

    $('#delete-measure').jqxWindow({
        title: '<strong>Confirm Lead Measure Unlinking</strong>',
        width: 300,
        height: 200,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny-measure")
    });
    
    $("#deny-measure").click(function() {
        $("#measures-list").jqxDataTable('updateBoundData');
    });
    
//    $("[id^=accept-measure]").click(function() {
//        var id = $(this).attr('id').split('-')[2];
//        var initiative = $("#initiative").val();
//        $.post("?r=alignment/unlinkLeadMeasure",
//                {measure: id, 
//                 initiative: initiative},
//        function(data) {
//            var response = $.parseJSON(data);
//            window.location = response.url;
//        });
//    });
});