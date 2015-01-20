$(document).ready(function() {
    $("#implem-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'office'},
                {name: 'action'}
            ],
            url: '?r=implementor/listOffices',
            type: 'POST',
            data: {
                initiative: $("#initiative").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Implementing Office</span>', dataField: 'office', width: '90%'},
            {text: '', dataField: 'action', width: '10%', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: 25,
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow'
    }).on("rowClick", function() {
        $("[id^=remove]").click(function() {
            var text = $(this).parent().siblings("td").html();
            $("#text").html("Do you want to delete this Implementing Office? Continuing will remove the Implementing Office, <strong>" + text + "</strong>, from the Initiative");
            $("#delete-implem").jqxWindow('open');
            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
        });
    });

    $('#delete-implem').jqxWindow({
        title: '<strong>Confirm Implementing Office Deletion</strong>',
        width: 300,
        height: 150,
        resizable: false,
        draggable: false,
        isModal: true,
        autoOpen: false,
        theme: 'office',
        animationType: 'none',
        cancelButton: $("#deny")
    });

    $("#deny").click(function() {
        $("#implem-list").jqxDataTable('updateBoundData');
    });
    
    $("[id^=accept]").click(function() {
        var id = $(this).attr('id').split('-')[1];
        var initiative = $("#initiative").val();
        $.post("?r=implementor/unlink",
                {id: id, 
                 initiative: initiative},
        function(data) {
            var response = $.parseJSON(data);
            window.location = response.url;
        });
    });

    $("#offices-input").jqxComboBox({
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
        animationType: 'none',
        multiSelect: true
    }).on('change', function() {
        var input = [];
        var items = $("#offices-input").jqxComboBox('getSelectedItems');
        var i = 0;
        $.each(items, function() {
            input[i] = this.value;
            i++;
        });
        $("#offices").val(input.join("/"));
    });

    $("#validation-container").hide();
    $(".ink-form").submit(function() {
        if ($("#offices").val().length === 0) {
            $("#validation-container").show();
            $("#validation-header").html("Validation error. Please check your entries.");
            $("#validation-content").html("Implementing offices should be defined");
            return false;
        } else {
            return true;
        }
    });
});