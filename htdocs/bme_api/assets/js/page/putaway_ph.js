
$(document).on('click','#btn-lotsearch',function(){
    /*
    $.post('putaway/findItemkey',{itemkey:$(this).val()},function(res){

    },'json');
    */
    lotbarcode();
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

$(document).on('click','.btnselect',function(){
    var mykey = $(this).data('txtbox');
    var data = $(this).data('info');

    if(mykey == 'lot'){
        $("#prodcode").val('(02)'+textval(data.ItemKey)+'(10)'+textval(data.LotNo));
        $("#lotno").val(textval(data.LotNo));
        $("#BinNo").val(textval(data.BinNo));
        $("#ItemKey").val(textval(data.ItemKey));
        $("#Location").val(textval(data.LocationKey));
        $("#uom").val(textval(data.Stockuomcode));
        $("#QtyOnHand").val(textval(data.QtyOnHand));
        $("#qty_available").val(textval(data.QtyOnHand) - textval(data.QtyCommitSales) );
        $("#ExpirationDate").val( textval(data.DateExpiry) );
        $('#PutAway_qty').val(data.QtyOnHand);
    }else{
        $("#"+mykey).val( textval(data.BinNo) );
    }

    
    $('.bootbox-close-button').click();
});

function lotbarcode(mm=''){
    
        $("#prodcode").val('');
        $("#lotno").val('');
        $("#ItemKey").val('');
        $("#BinNo").val('');
        $("#Location").val('');
        $("#uom").val('');
        $("#QtyOnHand").val('');
        $("#qty_available").val('');
        $("#ExpirationDate").val('');
        $('#PutAway_qty').val('');
        $('#ToBinNo').val('');
        $.post('putaway_ph/findlot',{prodcode:mm},function(res){
           
            switch(true){
                
                case ((res.data).length > 1 ):{
                    var column = [ 
                            {data: 'LotNo',title: '',visible: true,className: ''},
                            {data: 'ItemKey',title: 'ItemKey',visible: true,className: ''},
                            {data: 'LotNo',title: 'LotNo',visible: true,className: ''},
                            {data: 'BinNo',title: 'BinNo',visible: true,className: ''},
                            {data: 'QtyOnHand',title: 'QtyOnHand',visible: true,className: ''},
                            {data: 'DateReceived',title: 'Date Received',visible: true,className: ''},
                            {data: 'DateExpiry',title: 'DateExpiry',visible: false,className: ''},
                            {data: 'LocationKey',title: 'LocationKey',visible: false,className: ''},
                            {data: 'ItemKey',title: 'ItemKey',visible: false,className: ''},
                            {data: 'Stockuomcode',title: 'Stockuomcode',visible: false,className: ''},
                            {data: 'QtyCommitSales',title: 'QtyCommitSales',visible: false,className: ''},
				        ]; 

                        mydatatables('lot',column, res.data);
                         $('#PutAway_qty').focus();
                    break;
                }
                case (res.data.length == 1):{
                    //console.log((res.data).length);
                    var mydata = res.data[0];
                    $("#prodcode").val('(02)'+textval(mydata.ItemKey)+'(10)'+textval(mydata.LotNo));
                    $("#lotno").val(textval(mydata.LotNo));
                    $("#BinNo").val(textval(mydata.BinNo));
                    $("#ItemKey").val(textval(mydata.ItemKey));
                    $("#Location").val(textval(mydata.LocationKey));
                    $("#uom").val(textval(mydata.Stockuomcode));
                    $("#QtyOnHand").val(textval(mydata.QtyOnHand));
                    $("#qty_available").val(textval(mydata.QtyOnHand) - textval(mydata.QtyCommitSales) );
                    $("#ExpirationDate").val( textval(mydata.DateExpiry) );
                    $('#PutAway_qty').val(textval(mydata.QtyOnHand));
                    $('#PutAway_qty').focus();
                    break;
                }

                default:{
                    Swal.fire(
                        'No Record Available.',
                        '',
                        'warning'
                    );
                    break;
                }

            }
            
           

        },'json');
        
}

function binbarcode(mm = ''){
    if(mm.substring(0,5) == '(414)'){
        dd = mm.substring(5);
    }else{
        dd = mm;
    }

    $.post('putaway_ph/findBin',{BinNo:dd},function(res){

        switch(true){
            
            case ((res.data).length > 1 ):{
                var column = [ 
                        {data: 'BinNo',title: '',visible: true,className: ''},
                        {data: 'BinNo',title: 'BinNo',visible: true,className: ''},
                        {data: 'Description',title: 'Description',visible: true,className: ''},
                    ]; 

                    mydatatables('ToBinNo',column, res.data);
                break;
            }
            case (res.data.length == 1):{
                //console.log((res.data).length);
                var mydata = res.data[0];
                break;
            }

            default:{
                Swal.fire(
                    'Bin Not Found!',
                    '',
                    'warning'
                );
                $('#ToBinNo').val('');
                break;
            }

        }

    },'json');
}

var barcode = '';
var interval;

$("#btn-bin-search").click(function(){
    $.post('putaway_ph/findBin',{BinNo:$(this).val()},function(res){

        switch(true){
            
            case ((res.data).length > 1 ):{
                var column = [ 
                        {data: 'BinNo',title: '',visible: true,className: ''},
                        {data: 'BinNo',title: 'BinNo',visible: true,className: ''},
                        {data: 'Description',title: 'Description',visible: true,className: ''},
                    ]; 

                    mydatatables('ToBinNo',column, res.data);
                break;
            }
            case (res.data.length == 1):{
                //console.log((res.data).length);
                var mydata = res.data[0];
                break;
            }

            default:{
                Swal.fire(
                    'No Record Available.',
                    '',
                    'warning'
                );
                $('#ToBinNo').val('');
                break;
            }

        }

    },'json');
});



document.addEventListener('keydown', function(evt) {
    $focused = $(':focus');
   
    if (evt.key === 'F12'){
        evt.preventDefault();
    }
    if (interval){
        clearInterval(interval);
    }
    if (evt.key == 'Enter') {

        if (barcode){
            /*
            if($(this).attr('id') == 'ToBinNo'){
                $('#ToBinNo').val('').val(barcode);
            }else{
                $('#lot').val('').val(barcode);
                lotbarcode(barcode);
            }
            */
            if(($focused.attr('id') == 'PutAway_qty')){
                $('#ToBinNo').focus();
            }

            if(barcode.substring(0,4) != '(02)'){
                alert(barcode);
                $('#ToBinNo').val('').val(barcode.substring(5));
                binbarcode(barcode.substring(5));
            }else{
                $('#prodcode').val('').val(barcode);
                lotbarcode(barcode);
            }
                
        }
        barcode = '';
        return;
    }
    if (evt.key != 'Shift'){
        barcode += evt.key;
    }
    interval = setInterval(() => barcode = '', 20);
});

$("#btn-submit-putaway").click(function(){
    var lotno = $('#lotno').val();
    var putaway = $('#PutAway_qty').val();
    var tobin = $('#ToBinNo').val();
    var itemkey = $('#ItemKey').val();
    var bin = $('#BinNo').val();
   
    $.post("putaway_ph/sendputaway",{
        lotno : lotno,
        putaway : putaway,
        tobin : tobin,
        itemkey : itemkey,
        bin : bin,
    },function(res){

        if(res.msgno == 900){
            Swal.fire(
                res.msg,
                '',
                'success'
            );

            $("#prodcode").val('');
            $("#lotno").val('');
            $("#ItemKey").val('');
            $("#BinNo").val('');
            $("#Location").val('');
            $("#uom").val('');
            $("#QtyOnHand").val('');
            $("#qty_available").val('');
            $("#ExpirationDate").val('');
            $('#PutAway_qty').val('');
            $('#ToBinNo').val('');
        }else{
            Swal.fire(
                res.msg,
                '',
                'error'
            );
        }

        

    },'json').fail(function() {
        Swal.fire(
            'Oppps Something went wrong, Please contact the system Adminstrator',
            '',
            'error'
        );
    });
    
});