$(function(){
    if($("#searchFor").val()==""){
        $("#searchFor").focus();
    }
    
    
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
        $("#searchForm .loading").show();
        return true;
    })

    $("#smsForm").submit(function(){
        $(".errorDiv").remove();
        $(".errors").remove();
        var number=$("#number").val();
        if(number=="" || number=="0"){
            $("label[for='number']").before("<div class='errorDiv'>Please enter your Cellphone Number in the format xxx-xxx-xxxx</div>");
            return false;
        }
        return true;
    })

    $("#searchDepartments #department").change(function(){
         window.location="/site/departments/search/department/"+$(this).val();
    })

    $("#searchDepartments").submit(function(){
        var dept;
        dept=$("#department").val()
        if(dept=="" || dept=="0"){
            $("label[for='department']").before("<div class='errorDiv'>Please select a department</div>");
            return false;
        }
        return true;
    })

})