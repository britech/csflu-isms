$(document).ready(function(){
   
   $("#validation-container").hide();
   
   $(".ink-form").submit(function(){
       var data = $("#commitment").val();
       
       if(data === '' || data.length === 0){
           $("#validation-container").show();
           $("#validation-message").html("*&nbsp;Commitment should be defined");
           return false;
       } else {
           return true;
       }
   });
    
});