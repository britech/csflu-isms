$(document).ready(function(){
    $("#description-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'description'}
            ],
            url: '?r=map/listEnlistedPerspectives'
        }),
        valueMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px'
    }).on("select", function(event) {
        if (event.args) {
            $("#description").val(event.args.item.value);
        }
    }).on("bindingComplete", function(){
        $("#description-input").val($("#description").val());
    });
    
    $("#description-input").change(function(){
        $("#description").val($(this).val());
    });
});
