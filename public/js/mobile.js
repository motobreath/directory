$(document).bind("mobileinit", function() {
    // depreciated... $.mobile.touchOverflowEnabled = true;
    $.mobile.allowCrossDomainPages = true;
});
$(function(){
    $(".sendContactForm").live("submit",(function(){
        var email=$(this).find(".email").val();
        var sendTo=$(this).find(".sendTo").val();
        if(sendTo==""){
            $(this).find(".sendTo").focus();
            alert("Please enter an email");
            return false;
        }
        if(email=="" || !validateEmail(email)){
            alert("Error sending email. Please search again");
            return false;
        }
        if(!validateEmail(sendTo)){
            alert("Invalid Email");
            $(this).find(".sendTo").val("").focus();
            return false;
        }
        var data={
            "email":email,
            "sendTo":sendTo,
            "format":"json"
        }
        $.ajax("/site/email/sendcontact",{
            "type":"POST",
            "success":function(){
                alert("Message Sent");
            },
            "data":data,
            "error":function(jqXHR, textStatus, errorThrown){
                alert("your email was not sent. Please try refreshing the page");
            }
        })

        return false;
    }))
})

function validateEmail(elementValue){
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailPattern.test(elementValue);
}


