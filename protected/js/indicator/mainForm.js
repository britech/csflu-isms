$(document).ready(function() {
    $("#uomList").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=uom/listUnitofMeasures'
        }),
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    });
    $("#uomList").on('select', function(event) {
        if (event.args) {
            $("#uom-id").val(event.args.item.value);
        }
    }).on('bindingComplete', function() {
        var item = $("#uomList").jqxComboBox('getItemByValue', $("#uom-id").val());
        $("#uomList").jqxComboBox({
            selectedIndex: item.index
        });
    });
    
    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=indicator/validateIndicatorEntry",
            data: {"Indicator": {
                    'description': $("[name*=description]").val(),
                    'rationale' : $("[name*=rationale]").val(),
                    'formula' : $("[name*=formula]").val(),
                    'dataSource' : $("[name*=dataSource]").val(),
                    'dataSourceStatus' : $("[name*=dataSourceStatus]").val(),
                    'dataSourceAvailabilityDate' : $("[name*=dataSourceAvailabilityDate]").val()
                   },
                   "UnitOfMeasure":{
                       'id':$("#uom-id").val()
                   },
                   "mode": $("[name*=validationMode]").val()},
            async: false,
            success: function(data) {
                try {
                    response = $.parseJSON(data);
                    if (response.respCode === '00') {
                        $("#description").val($("#description-input").val());
                        $("#positionOrder").val($("#position-order").val());
                    }
                    result = response.respCode === '00';
                } catch (e) {
                    $("#validation-container").html(data);
                }
            }
        });

        return result;
    });
});

