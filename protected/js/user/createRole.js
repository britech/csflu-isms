$(document).ready(function() {
    $("#validation-error").hide();
    $(".ink-form").submit(function() {
        var count = 0;
        $("#content").html("");
        if($("[name*=description]").val().length === 0) {
            count += 1;
            $("#content").append("- Description field should not be empty<br/>");
        }
        
        if ($(":checked").size() === 0) {
            count += 1;
            $("#content").append("- At least one action should be linked to the security role<br/>");
        }
        
        if(count > 0){
            $("#validation-error").show();
            $("[name*=description]").focus();
            return false;
        }
    });
});