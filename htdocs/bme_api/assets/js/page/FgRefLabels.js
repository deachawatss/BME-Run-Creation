function loadProdData(){
    $.post('FgRefLabels/prodlookup',{custkey:$("#custkey").val()},function(res){
        
        $("#itemkey").empty().select2({
            theme: 'bootstrap4',
            dropdownParent:$("#itemkey").parent(),
            data:res,
            placeholder: {
                id: '', // the value of the option
                text: 'Please Select'
            },
            allowClear: true
                            
        });

    },"json");

}

$(document).on('change',"#custkey",function(){
    loadProdData();
});