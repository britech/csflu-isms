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
        $("#phase-input").val($("#phase").val());
    });

    $("#component-list").jqxDataTable({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'component'},
                {name: 'phase'},
                {name: 'actions'}
            ],
            url: '?r=project/listComponents',
            type: 'POST',
            data: {
                initiative: $("#initiative").val()
            }
        }),
        columnsresize: false,
        theme: 'office',
        columns: [
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Component</span>', dataField: 'component', width: '80%'},
            {text: '<span style="text-align:center; display: block; font-weight: bold;">Actions</span>', dataField: 'actions', cellsAlign: 'center'}
        ],
        width: '100%',
        pageable: true,
        pageSize: '20',
        filterable: true,
        filterMode: 'simple',
        sortable: true,
        selectionMode: 'singleRow',
        groups: ['phase'],
        groupsRenderer: function(value, rowData, level) {
            return "<strong>" + value + "</strong>";
        }
    }).on("rowClick", function() {
//        $("[id^=remove]").click(function() {
//            var text = $(this).parent().siblings("td").html();
//            $("#text").html("Do you want to delete this objective? Continuing will remove the objective, <strong>" + text + "</strong>, in the Strategy Map")
//            $("#delete-objective").jqxWindow('open');
//            $("#accept").prop('id', "accept-" + $(this).attr('id').split('-')[1]);
//        });
    });
    
    $("#validation-container").hide();

    $(".ink-form").submit(function() {
        var component = $("[name*=description]").val();
        var phase = $("#phase").val();
        
        if(component === '' && phase === ''){
            $("#validation-content").html("-&nbsp;Component should be defined<br/>-&nbsp;Phase should be defined");
            $("#validation-container").show();
            return false;
        } else if(component === ''){
            $("#validation-content").html("-&nbsp;Component should be defined");
            $("#validation-container").show();
            return false;
        } else if(phase === ''){
            $("#validation-content").html("-&nbsp;Phase should be defined");
            $("#validation-container").show();
            return false;
        }
        return true;
    });
});