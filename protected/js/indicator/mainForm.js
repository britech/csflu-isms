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
});

