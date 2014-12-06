$(document).ready(function(){
    $("#description-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'}
            ],
            url: '?r=perspective/listPerspectives'
        }),
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("#description").val(event.args.item.label);
        }
    }).on("bindingComplete", function(){
        $("#description-input").val($("#description").val());
    });
    
    $("#description-input").change(function(){
        $("#description").val($(this).val());
    });
});
