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
        selectedIndex: 0,
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px'

    });
    $("#securityRole-list").on('select', function(event){
       if(event.args){
           $("#securityRole-id").val(event.args.item.value);
       } 
    });
    
    $("#department-list").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=department/listDepartments'
        }),
        selectedIndex: 0,
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px'

    });
    $("#department-list").on('select', function(event){
       if(event.args){
           $("#department-id").val(event.args.item.value);
       } 
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
        selectedIndex: 0,
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px'

    });
    $("#position-list").on('select', function(event){
       if(event.args){
           $("#position-id").val(event.args.item.value);
       } 
    });
});

