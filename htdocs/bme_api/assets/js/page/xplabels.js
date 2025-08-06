function after_addfn(data){
    window.open('xplabels/print?id='+data.newdata);
}

$(document).on("click",".btn-reprint-tbl_xp_labels",function(){
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
                    window.open('xplabels/reprint?id='+myid+'&pstart='+$("#pstart").val()+"&pend="+$("#pend").val());
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