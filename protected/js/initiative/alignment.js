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
    $(".ink-form").submit(function(){
        var objectives = $("#objectives").val();
        var measures = $("#measures").val();
        
        if(objectives === '' && measures === ''){
            $("#validation-container").show();
            $("#validation-header").html("Validation error. Please check your entries");
            $("#validation-content").html("An Objective or Measure should be selected");
            return false;
        }
    });
});