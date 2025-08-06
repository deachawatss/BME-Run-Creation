var column =[ 
    {data: 'LT.LotTranNo',title: 'Action',visible: true,className: ''},
    {data: 'LT.ReceiptDocNo',title: 'Doc no',visible: true,className: ''},
    {data: 'LT.ItemKey',title: 'Item Key',visible: true,className: ''},
    {data: 'IM.Desc1',title: 'Description',visible: true,className: ''},
    {data: 'LT.LotNo',title: 'Lot No',visible: true,className: ''},
    {data: 'LT.Vendorkey',title: 'Vendor',visible: true,className: ''},
    {data: '$.btn',title: 'BTN',visible: false,className: ''},
  //  {data: 'LT.QtyReceived',title: 'Vendor Name',visible: false,className: ''},
   // {data: 'LT.DateReceived',title: 'Vendor Name',visible: false,className: ''},
   // {data: 'LT.DateExpiry',title: 'Vendor Name',visible: false,className: ''},
   //  {data: 'IQ.ToKey',title: 'Vendor Name',visible: false,className: ''},
   // {data: 'IQ.Convfctr',title: 'Vendor Name',visible: false,className: ''},
   // {data: 'QL.Status',title: 'Vendor Name',visible: false,className: ''},
   // {data: 'QR.Tested_By',title: 'Vendor Name',visible: false,className: ''},
   // {data: 'IPD.PropDesc',title: 'Vendor Name',visible: false,className: ''},
]; 

APP.Datatable.defaultOpts.url="/bme/RMLabel2/dataTable";
APP.Datatable.defaultOpts.column=column;
var mdiag;

$(document).on('click','.btn-select',function(){
    mdiag.modal('hide');
    var mydata = $(this).data('info');
    $("#receiptdoc").val(mydata.ReceiptDocNo);
    getdatainfo(mydata.LotNo, mydata.ItemKey);
});

$(document).on('click','.btnselect',function(){
    var mykey = $(this).data('txtbox');
    var data = $(this).data('info');

        $("#desc1").val(data.Desc1);
        $("#ItemKey").val(data.ItemKey);
        $("#lotno").val(data.LotNo);
        $("#vendname").val(data.Vendorkey);
        $("#vendlotno").val(data.VendorlotNo);
        $("#prno").val(data.ReceiptDocNo);
        $("#date_exp").val(data.DateExpiry);
        $("#qty_rec").val(data.QtyReceived);
        $("#allergen").val(data.PropDesc);
        $("#pack_type").val(data.ToKey);
        $("#pack_size").val(data.Convfctr);
        $('#LotTranNo').val(data.LotNo);
   

    
    $('.bootbox-close-button').click();
});

var mdiag;
function mydatatables(keybtn = '',column = [], data = {}){
    
    var html = '<table id="mytable" class="table table-sm table-bordered table-hover"></table>';

    mdiag = bootbox.dialog({
        'message': html,
        'size': 'lg'

    })

    mdiag.on('shown.bs.modal', function(e){
        $("#mytable").DataTable({
            columns : column,
            "processing": true,
            destroy: true,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pageLength: 10,
            data: data,
            columnDefs: [
                {
                    render: function (data, type, row) {
                        return "<a class='btn btn-sm btn-outline-success btnselect' data-info='"+JSON.stringify(row)+"' data-txtbox='"+keybtn+"' ><i class='fas fa-check' ></i></a>";
                    },
                    targets: 0,
                }
            ]
        });
    });

}

function getdatainfo(lotno = '',itemkey =''){
    //findtransno
    $("#desc1").val('');
    $("#ItemKey").val('');
    $("#lotno").val('');
    $("#vendname").val('');
    $("#vendlotno").val('');
    $("#prno").val('');
    $("#date_exp").val('');
    $("#qty_rec").val('');
    $("#allergen").val('');
    $("#pack_type").val('');
    $("#pack_size").val('');
    $('#LotTranNo').val('');
    $('#LocationKey').val('');

    $.post('RMLabel2/findtransno',{itemkey:itemkey, lotno:lotno},function(res){
       var recx = res.data;
        if(res.msgno == "100" ){
            $("#desc1").val(recx.Desc1);
            $("#ItemKey").val(recx.ItemKey);
            $("#lotno").val(recx.LotNo);
            $("#vendname").val(recx.Vendorkey);
            $("#vendlotno").val(recx.VendorlotNo);
            $("#prno").val(recx.ReceiptDocNo);
            $("#date_exp").val(recx.DateExpiry);
            $("#qty_rec").val(recx.QtyReceived);
            $("#allergen").val(recx.PropDesc);
            $("#pack_type").val(recx.ToKey);
            $("#pack_size").val(recx.Convfctr);
            $("#LocationKey").val(recx.LocationKey);
        }else{
            Swal.fire(
                'No Record Available.',
                '',
                'warning'
            );
        }
        
    },'json');
}

function getsearchdata(mm = ''){
    $("#desc1").val('');
    $("#ItemKey").val('');
    $("#lotno").val('');
    $("#vendname").val('');
    $("#vendlotno").val('');
    $("#prno").val('');
    $("#date_exp").val('');
    $("#qty_rec").val('');
    $("#allergen").val('');
    $("#pack_type").val('');
    $("#pack_size").val('');
    $('#qc_status').val('');
    $('#tested_by').val('');
    $('#LotTranNo').val('');
    $('#LocationKey').val('');

    $.post('RMLabel2/findreceiptdoc',{ReceiptDocNo:mm},function(res){
        var recx = res.data;
         if(res.msgno == "100" ){

            var xcolumn = [ 
                {data: 'LotTranNo',title: 'Action',visible: true,className: ''},
                {data: 'ReceiptDocNo',title: 'Doc no',visible: true,className: ''},
                {data: 'ItemKey',title: 'Item Key',visible: true,className: ''},
                {data: 'Desc1',title: 'Description',visible: true,className: ''},
                {data: 'LotNo',title: 'Lot No',visible: true,className: ''},
                {data: 'Vendorkey',title: 'Vendor',visible: true,className: ''},
                {data: 'QtyReceived',title: 'Vendor Name',visible: false,className: ''},
                {data: 'DateReceived',title: 'Vendor Name',visible: false,className: ''},
                {data: 'DateExpiry',title: 'Vendor Name',visible: false,className: ''},
                {data: 'ToKey',title: 'Vendor Name',visible: false,className: ''},
                {data: 'Convfctr',title: 'Vendor Name',visible: false,className: ''},
            ]; 

            mydatatables('receiptdoc',xcolumn, res.data);
           
         }else{
             Swal.fire(
                 'No Record Available.',
                 '',
                 'warning'
             );
         }
         
     },'json');

}

$("#btn-lotsearch").click(function(){
    mdiag = bootbox.dialog({
        message:'<table class="table table-sm table-bordered table-hovered" id="tbl_search"></table>',
        size:'lg'
    
    });
    
    mdiag.on('shown.bs.modal', function(e){
        dt_tbl_user = APP.Datatable.init('tbl_search');
    });
    
});

$("#receiptdoc").keydown(function(evt){
    
    if (evt.code == 'Enter') {
        if($(this).val() != ''){
            var msrch = $(this).val();
            getsearchdata(msrch);
        }
    }
});

/*
$("#receiptdoc").blur(function(evt){
    
    if($(this).val() != ''){
        var msrch = $(this).val();
        getsearchdata(msrch);
    }
});
*/

$("#btn-print").click(function(){
   // var qc_status = $("#qc_status").val();
    var qty_rec = $("#qty_rec").val();
    var pack_size = $("#pack_size").val();
    var pack_type = $("#pack_type").val();
    var itemkey = $("#ItemKey").val();
    var LotNo = $("#lotno").val();
    var LocationKey = $("#LocationKey").val();
    //var FeatureId = $("#").val();
    var rec = false;
    
    if(pack_size == 0 || pack_type == "" ){

        bootbox.dialog({
            title: 'Please complete details.',
            message:'<table class="table table-bordered">'+
                    '<tr><td>Pack Type</td>'+
                        '<td>'+
                        '<select class="form-control" id="fpack_type">'+
                            '<option value="BAG">BAG</option>'+
                            '<option value="BOTTLE">BOTTLE</option>'+
                            '<option value="BOX">BOX</option>'+
                            '<option value="CARBOUY">CARBOUY</option>'+
                            '<option value="CARTON">CARTON</option>'+
                            '<option value="DRUM">DRUM</option>'+
                            '<option value="GALLON">GALLON</option>'+
                            '<option value="PAIL">PAIL</option>'+
                            '<option value="PLASTIC">PLASTIC</option>'+
                            '<option value="ROLLS">ROLLS</option>'+
                            '<option value="TOTE">TOTE</option>'+
                        '</select>'+
                    '</td>'+
                    '</tr>'+
                    '<tr><td>Pack Size</td><td><input type="number" id="fpack_size" class="form-control" ></td></tr>',
            buttons: {
                ok: {
                    label: "<i class='fas fa-save'></i>&nbsp;Save",
                    className: 'btn-success',
                    callback: function() {
                        if(
                            $("#fpack_type").val() != "" && 
                            itemkey != "" && 
                            LotNo != "" && 
                            LocationKey != "" && 
                            $("#fpack_size").val() != ""
                        ){
                            $.post('RMLabel2/updateFeatures',{
                                    pack_type:$("#fpack_type").val(), 
                                    pack_size:$("#fpack_size").val(),
                                    itemkey:itemkey,
                                    LotNo:LotNo,
                                    LocationKey:LocationKey
                            },function(res){
                                if(res.msgno == 900){

                                    rec = true
                                }else{
                                    Swal.fire(
                                        'NWF Mobile',
                                        res.msg,
                                        'Warning'
                                      )
                                }

                            },"json");
                        }else{

                        }
                       
                    }
                },
                cancel: {
                    label: "<i class='fas fa-window-close'></i>&nbsp;Cancel",
                    className: 'btn-danger',
                    callback: function(){
                        
                    }
                }
            }
        });

    }else{
        rec = true;
    }

    if(rec == true){
        bootbox.prompt({ 
            title:'How may Stickers?', 
            inputType:'number',
            value: (qty_rec / pack_size),
            callback: function (result) {
                if(result){
                    bootbox.dialog({
                        size:'xl',
                        message:"<iframe id='xx' src='"+ 'RMLabel2/print?count='+result+'&lotno='+$('#lotno').val() +"&ItemKey="+$('#ItemKey').val() +"' style='width:100%;min-height:75vh'></iframe>",
                    })
                }
            }
        });
    }
       
    
});