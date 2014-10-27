$(document).ready(function() {

    $("#securityRole-list").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=role/listSecurityRoles'
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
    $("#securityRole-list").on('select', function(event) {
        if (event.args) {
            $("#securityRole-id").val(event.args.item.value);
        }
    }).on('bindingComplete', function() {
        var item = $("#securityRole-list").jqxComboBox('getItemByValue', $("#securityRole-id").val());
        $("#securityRole-list").jqxComboBox({
            selectedIndex: item.index
        });
    });

    $("#position-list").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=position/listPositions'
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
    $("#position-list").on('select', function(event) {
        if (event.args) {
            $("#position-id").val(event.args.item.value);
        }
    }).on('bindingComplete', function() {
        var item = $("#position-list").jqxComboBox('getItemByValue', $("#position-id").val());
        $("#position-list").jqxComboBox({
            selectedIndex: item.index
        });
    });
});

