$(document).ready(function() {
    $("#baselineTable").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'year'},
                {name: 'group'},
                {name: 'figure'},
                {name: 'action'}
            ],
            url: '?r=indicator/listBaselines',
            type: 'POST',
            data: {
                id: $("#indicator-id").val(),
                action: 1
            }
        }),
        columnsresize: false,
        theme: 'office',
        groups: ['year'],
        groupsRenderer: function(value, rowData, level) {
            return "Year Covered:&nbsp;" + value;
        },
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Item</span>', dataField: 'group', width: '20%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Value</span>', dataField: 'figure'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;"></span>', dataField: 'action'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 50
    });
    
    $("#year").jqxNumberInput({
        inputMode: 'simple',
        spinButtons: true,
        min: 2010,
        max: 2100,
        decimalDigits: 0,
        height: '35px',
        textAlign: 'left'
    }).val($("#yearValue").val());
    
    $("#year").on('valuechanged', function(event){
        $("#yearValue").val(event.args.value);
    });
    
    $("#notes").hide();
    $("#show-dialog").click(function(){
       $("#notes").show();
       $("#divider").hide();
       $(this).hide();
    });
    $(".ink-dismiss").click(function(){
       $("#notes").hide();
       $("#divider").show();
       $("#show-dialog").show();
    });
});