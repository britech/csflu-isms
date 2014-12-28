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

    $(".ink-form").submit(function() {
        var result = false;

       
        var designationValues = [];
        designationValues.length = $("[name*=designation]:checked").length;
        var i = 0;
        $("[name*=designation]").each(function() {
            if ($(this).is(":checked")) {
                console.log($(this).val());
                designationValues[i] = $(this).val();
                i++;
            }
        });

        $.ajax({
            type: "POST",
            url: "?r=measure/validateLeadOfficeInput",
            data: {"LeadOffice": {
                    'designation': $("[name*=designation]:checked").length === 0 ? "" : designationValues
                },
                "Department": {
                    'id': $("#department").val()
                }
            },
            async: false,
            success: function(data) {
                try {
                    response = $.parseJSON(data);
                    result = response.respCode === '00';
                } catch (e) {
                    $("#validation-container").html(data);
                }
            }
        });

        return result;
    });
});