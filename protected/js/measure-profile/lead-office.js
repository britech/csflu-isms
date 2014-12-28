$(document).ready(function() {
    $("#department-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=department/listDepartments'
        }),
        valueMember: 'id',
        displayMember: 'name',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("[name*=department]").val(event.args.item.value);
        }
    });
    
    $("#lead-offices").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'department'},
                {name: 'designation'}
            ],
            url: '?r=measure/listLeadOffices',
            type: 'POST',
            data: {
                profile: $("#profile").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        groups: ['designation'],
        groupsRenderer: function(value, rowData, level) {
            return "Year Covered:&nbsp;" + value;
        },
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Lead Office</span>', dataField: 'department'}
        ],
        width: '100%',
        pageable: true
    });
});