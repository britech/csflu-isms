$(document).ready(function() {
    $("#phase-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'phase'}
            ],
            url: '?r=project/listPhases',
            type: 'POST',
            data: {
                initiative: $("#initiative").val()
            }
        }),
        valueMember: 'id',
        displayMember: 'phase',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            console.log(event.args.item.value);
            $("#phase").val(event.args.item.value);
        }
    }).on("bindingComplete", function() {
        $("#description-input").val($("#description").val());
    });
});