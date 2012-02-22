$(function(){
    $("#sendSMS").click(function(e){
        $("#sendSMSForm").show();
        e.preventDefault();
        return false;
    })
    $("#searchForm").submit(function(){
        $(".errorDiv").remove();
        var searchBy=$("#searchBy").val();
        if(searchBy==""){
            $("label[for='searchBy']").before("<div class='errorDiv'>Search By cannot be blank</div>");
            return false;
        }

        var searchFor=$("#searchFor").val();
        if(searchFor==""){
            $("label[for='searchFor']").before("<div class='errorDiv'>Please enter in at least 2 characters</div>");
            return false;
        }

        return true;
    })
    //TODO: Validate sms form client side
    $("#smsForm").submit(function(){

    })

    $("#searchDepartments").submit(function(){
        var dept=$("#department").val();
        if(dept=="" || dept=="0"){
            $("label[for='department']").before("<div class='errorDiv'>Please select a department</div>");
            return false;
        }
    })
})