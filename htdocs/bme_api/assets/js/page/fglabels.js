function loadProdData(){

    $.post('fglabels/prodlookup',{custkey:$("#custkey").val()},function(res){
        
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

function after_addfn(data){
    window.open('fglabels/print?id='+data.newdata);
}

$(document).on("click",".btn-reprint-tbl_fg_labels",function(){
    var myid= $(this).data("id");

    bootbox.dialog({
        "title": "Re-Print Label",
        "message":"<table>"+
                    "<tr><td>Print From</td><td><input type='number' id='pstart' class='form-control'/></td></tr>"+
                    "<tr><td>Print To</td><td ><input type='number' id='pend' class='form-control'/></td></tr>"+
                    "</table>",
        "buttons":{
            Submit: {
                label: "<i class='fas fa-print'></i> &nbsp;Print",
                className: 'btn-outline-success',
                callback: function(){
                    window.open('fglabels/reprint?id='+myid+'&pstart='+$("#pstart").val()+"&pend="+$("#pend").val());
                }
            },

            Cancel: {
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },


        }
    });
});