$(document).ready(function() {
    $("#help-dialog").hide();

    $("#open-help").click(function() {
        $("#help-dialog").show();
    });

    $(".ink-dismiss").click(function() {
        $("#help-dialog").hide();
    });

    $("#empId").blur(function() {
        var id = $(this).val();
        $.post('?r=user/validateEmployee',
                {'id': id},
        function(data) {
            var response = $.parseJSON(data);
            if (response.respCode !== '00') {
                $("#empId").siblings(".tip").html('<i class="fa fa-warning"></i>&nbsp;'+response.respMessage);
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
                $("[name*=username]").val((response.givenName.substring(0,1) + "" +response.middleName.substring(0,1) + "" + response.lastName).toLowerCase());
                $("[name*=password]").val($("[name*=username]").val());
                $("#securityRole").prop('disabled', false);
                $("#position").prop('disabled', false);
            }
        });


    });
});