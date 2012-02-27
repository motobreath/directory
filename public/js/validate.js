$(function(){
    $("#searchForm select[name='searchBy']").change(function(){
        if($(this).val()=="telephone"){
             $("label[for='searchFor']").before("<div class='searchOptionTxt'>For best results, use format: xxx-xxx-xxxx</div>");
        }
        else{
            $("#searchForm .searchOptionTxt").remove();
        }
    })
    $("#searchForm").submit(function(){
        $(".errorDiv").remove();
        var searchBy=$("#searchBy").val();
        if(searchBy==""){
            $("label[for='searchBy']").before("<div class='errorDiv'>Search By cannot be blank</div>");
            return false;
        }

        var searchFor=$("#searchFor").val();
        if(searchFor.length<2){
            $("label[for='searchFor']").before("<div class='errorDiv'>Please enter in at least 2 characters</div>");
            return false;
        }

        if(searchBy=="telephone" && searchFor.length<4){
            $("label[for='searchFor']").before("<div class='errorDiv'>Please enter in at least 4 characters</div>");
            return false;
        }

        return true;
    })

    $("#smsForm").submit(function(){

    })

    $("#searchDepartments").submit(function(){
        var dept=$("#departments").val();
        if(dept==""){
            $("label[for='department']").before("<div class='errorDiv'>Please select a department</div>");
            return false;
        }
    })
})