$(document).ready(function() {
    $("#help-dialog").hide();

    $("#open-help").click(function() {
        $("#help-dialog").show();
    });

    $(".ink-dismiss").click(function() {
        $("#help-dialog").hide();
    });

    $("[type=submit]").prop('disabled', true);

    $("#empId").blur(function() {
        var id = $(this).val();
        if (id.length === 0) {
            $("[type=submit]").prop('disabled', true);
            $("#empId").siblings(".tip").html('<i class="fa fa-warning"></i>&nbsp;Please provide an Employee ID');
            $("#empId").focus();
        } else {
            $.post('?r=user/validateEmployee',
                    {'id': id},
            function(data) {
                var response = $.parseJSON(data);
                if (response.respCode !== '00') {
                    $("#empId").siblings(".tip").html('<i class="fa fa-warning"></i>&nbsp;' + response.respMessage);
                    $("#empId").focus();
                } else {
                    $("#empId").siblings(".tip").html('');
                    $("#empId").prop('disabled', 'disabled');
                    $("[name=name]").siblings("[name*=id]").val(response.id);
                    $("[name*=lastName]").val(response.lastName);
                    $("[name*=givenName]").val(response.givenName);
                    $("[name*=middleName]").val(response.middleName);
                    $("#name").val(response.givenName + " " + response.lastName);

                    $.post('?r=department/getDepartmentByCode',
                            {'code': response.deptCode},
                    function(data) {
                        var response = $.parseJSON(data);
                        if (response.respCode !== '00') {
                            $("[name=department]").val('Unknown');
                        } else {
                            $("[name=department]").val(response.name);
                            $("[name=department]").siblings("[name*=id]").val(response.id);
                        }
                    });
                    $("[name*=username]").val(response.username);
                    $("#securityRole").prop('disabled', false);
                    $("#position").prop('disabled', false);
                    $("[type=submit]").prop('disabled', false);
                    $("#securityRole-list").jqxComboBox({disabled: false});
                    $("#position-list").jqxComboBox({disabled: false});
                }
            });
        }
    });
    
    $("#securityRole-list").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'name'}
            ],
            url: '?r=role/listSecurityRoles'
        }),
        selectedIndex: -1,
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        disabled: true
    }).on('select', function(event){
       if(event.args){
           $("#securityRole-id").val(event.args.item.value);
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
        selectedIndex: -1,
        displayMember: 'name',
        valueMember: 'id',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        disabled: true
    }).on('select', function(event){
       if(event.args){
           $("#position-id").val(event.args.item.value);
       } 
    });
});