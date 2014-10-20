$(document).ready(function() {
    $("#btnSubmit").hide();

    $("[name=oldPassword]").blur(function() {
        $.post('?r=user/checkPassword',
                {password: $(this).val()},
        function(data) {
            try {
                var response = $.parseJSON(data);
                if (response.respCode !== '00') {
                    $("#oldPassword-tip").html('<i class="fa fa-warning"></i>&nbsp;' + response.respMessage);
                    $("[name=oldPassword]").focus();
                    $("[name=oldPassword]").prop('disabled', false);
                    $("[name=newPassword]").prop('disabled', true);
                    $("[name=confirmPassword]").prop('disabled', true);
                    $("#btnSubmit").hide();
                } else {
                    $("#oldPassword-tip").html('');
                    $("[name=oldPassword]").prop('disabled', true);
                    $("[name=newPassword]").prop('disabled', false).focus();
                    $("[name=confirmPassword]").prop('disabled', false);
                    $("#btnSubmit").show();
                    $("#btnSubmit").prop('disabled', true);
                }
            } catch (e) {
                $("#oldPassword-tip").html('<i class="fa fa-warning"></i>&nbsp;Please provide your current password');
                $("[name=oldPassword]").focus();
                $("[name=oldPassword]").prop('disabled', false);
                $("[name=newPassword]").prop('disabled', true);
                $("[name=confirmPassword]").prop('disabled', true);
                $("#btnSubmit").hide();
            }
        }
        );
    });

    $("[name=newPassword]").blur(function() {
        if ($(this).val() !== null && $(this).val().length > 0) {
            $("#newPassword-tip").html("");
        } else {
            $("#newPassword-tip").html("Please provide a new password");
            $(this).focus();
        }
    });

    $("[name=confirmPassword]").blur(function() {
        var newPassword = $("[name=newPassword]").val();

        if ($(this).val() !== newPassword) {
            $("#btnSubmit").prop('disabled', true);
            $("#confirmPassword-tip").html("Passwords don't match. Please try again.");
        } else {
            $("#btnSubmit").prop('disabled', false);
            $("#confirmPassword-tip").html("");
            $("#password").val($(this).val());
        }
    });

});