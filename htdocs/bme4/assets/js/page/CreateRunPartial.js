$(".btn-add-tbl_runhrd_partial").off("click");

var dta;
var dtax;
var mydata = [];
$(document).off('click','.btn-edit-tbl_runhrd_partial')


$(document).on("click",".btn-add-tbl_runhrd_partial",function(){
    mydata = [];
    var dbox = bootbox.dialog({
        title : 'Create Run',
        size : 'lg',
        message : `
            <table class='table table-sm table-bordered table-hovered'>
                <tr>
                    <!--<td>Run No:</td><td><input class='form-control form-control-sm' readonly id='runno'/></td>-->
                    <td>Formula ID:</td><td><input class='form-control form-control-sm' readonly id='formulaid'/></td>
                    <td>Batch Size:</td><td><input class='form-control form-control-sm' readonly id='batchsize'/></td>
                </tr>
                <tr>
                    <td>Total Batches:</td><td><input class='form-control form-control-sm' readonly id='total_batch'/></td>
                    <td>Total Batches Size:</td><td><input class='form-control form-control-sm' readonly id='total_batch_size'/></td>
                </tr>
                <tr>
                    <td colspan='4'><a class='btn btn-outline-success btn-lg ' id='btn-search' data-action = 'edit'><i class='fas fa-search'></i>&nbsp;Batch Search</a></span>
                </tr>
            </table>
            <input type='hidden' id='erunno' value=''/>
            <table class='table table-bordered table-hovered table-sm' id='nbllist'>
                <thead class='text-center'>
                    <th></th>
                    <th>Batch No</th>
                    <th>Formula ID</th>
                    <th>Batch Weight</th>
                    <th>Date</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        `,

        buttons : {
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;ADD",
                className: 'btn-outline-success',
                callback: function(){
                    formulaid = $("#formulaid").val();
                    batchwt = $("#batchsize").val();
                    batchlist = [];

                    $.each(dtax.rows().data(),function(k,v){
                        batchlist.push({
                            'batchno' : v[1],
                            'formulaid' : v[2],
                            'batchwt' : v[3]
                        });
                    });

                    var formData = new FormData($('#autoFrm')[0]);
                    formData.append('formulaid',formulaid);
                    formData.append('batchwt',batchwt);
                    formData.append('batchlist',JSON.stringify(batchlist));
                    formData.append('type','A');

                    $.ajax({
                        type: "POST",
                        url: 'CreateRunPartial/saverun',
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            
                        },
                        success: function(msg) {
                            var mymsg = $.parseJSON(msg);

                           if(mymsg.msgno != undefined){

                                Swal.fire(
                                    mymsg.msg,
                                    '',
                                    (mymsg.msgno == 100 ? 'success' : 'warning' )
                                ).then((result)=>{

                                });

                           }else{
                                Swal.fire(
                                    'Successfully added',
                                    '',
                                    'success'
                                ).then((result)=>{


                                });
                           }

                            APP.Datatable.reDraw(APP.Forms.defaultOpts.frm);
                            
                        },
                        error: function(msg) {
                            Swal.fire(
                                'Oppps Something went wrong, Please contact the system Adminstrator',
                                '',
                                'error'
                            );
                        }
                    });

                    
                }
            },
            Cancel: {
                label: "<i class='fas fa-window-close'></i> &nbsp;CLOSE",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        }
    });

    dbox.on('shown.bs.modal', function(e){
        dtax = $("#nbllist").DataTable();
    })

});

$(document).on('click','#btn-search',function(){

    var mparam = {};
    var act = $(this).data('action');

    if($('#formulaid').val() != ''){
        mparam = {
            formulaid : $('#formulaid').val(),
            batchwt : parseInt($('#batchsize').val()).toString(),
            erunno : $("#erunno").val()
        };
    }else{
        mparam = {
            formulaid : '',
            batchwt : '',
            erunno : ''
        };
    }

    
    $.post('CreateRunPartial/getBatchList',mparam,function(res){
        var dbox = bootbox.dialog({
            title : 'Create Run',
            size : 'lg',
            message : `
                        <table class='table table-bordered table-hovered table-sm' id='bllist'>
                        <thead class='text-center'>
                            <th>Batch No</th>
                            <th>Formula ID</th>
                            <th>Batch Weight</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            `+genBatchList(res)+`
                        </tbody>
                    </table>
                `,
                buttons : {
                    Submit: {
                        label: "<i class='fas fa-save'></i> &nbsp;ADD",
                        className: 'btn-outline-success',
                        callback: function(){
                            //if(act == 'add')
                            dtax.clear();

                            var rows_selected = dta.rows('.selected').data();
                            mydata = [];
                            $.each(rows_selected,function(k,v){

                                batchopt = "<a class='btn btn-outline-danger btn-remove'><i class='fas fa-trash'></i></a>";
                                batchno = v[0];
                                formulaid = v[1];
                                batchwt = v[2];
                                batchdate = v[3];
                                
                                mydata.push([batchopt,batchno,formulaid,batchwt,batchdate]);
                            });
                            dtax.rows.add(mydata).draw(false);
                        }
                    },
                    Cancel: {
                        label: "<i class='fas fa-window-close'></i> &nbsp;CLOSE",
                        className: 'btn-outline-danger ',
                        callback: function(){
                            
                        }
                    },
                } 
            });
            
            dbox.on('shown.bs.modal', function(e){
                dta = $("#bllist").DataTable();

                if(dtax.rows().data().length > 0){
                    //console.log($('#formulaid').val())
                    //var spl = dtax.rows().data()[0];
                    dta.columns(1).search($('#formulaid').val(),false);
                    dta.columns(2).search( parseInt($('#batchsize').val()).toString(),false).draw();
                }
                
                dta.on('click', 'tbody tr', function (e) {
                    e.currentTarget.classList.toggle('selected');
                    myformula = $(e.currentTarget).find('td:eq(1)').text();
                    mywt = $(e.currentTarget).find('td:eq(2)').text();
                    //var rows_selected = dta.rows({ selected: true }).data();
                    var rows_selected = dta.rows('.selected').data();
                    //console.log(mywt,rows_selected.length )
                    if(rows_selected.length  == 1){
                        dta.columns(1).search(myformula,false);
                        dta.columns(2).search(mywt,false).draw();
                        
                        var mydta = dta.rows('.selected').data()[0];
    
                        $('#formulaid').val(mydta[1]);
                        $('#batchsize').val(mydta[2]);
    
                    }
                    else if(rows_selected.length  > 1){
    
                    }else{
                        dta.columns(2).search('',false);
                        dta.columns(1).search('',false).draw();
                        $('#formulaid').val('');
                        $('#batchsize').val(0);
                    }
    
                    $("#total_batch").val(rows_selected.length);
                    $('#total_batch_size').val($('#batchsize').val() * rows_selected.length)
                });
            });
    },'json');

});


function genBatchList(param = {}){
    html = '';
    var x = [];
   
    $.each(param,function(k,v){


        if(dtax.rows().length > 0){
            var xd = dtax.rows().data()[0];

            $.each(dtax.rows().data(),function(k,v){
                x.push(v[1]);
                
            });
        
            html += `
            <tr `+ (x.indexOf(v.BatchNo) > -1 ? 'class="selected"' : '' ) +`>
                <td>${v.BatchNo}</td>
                <td>${v.FormulaID}</td>
                <td>${v.BatchWeight}</td>
                <td>${v.SchStartDate}</td>
            </tr>
            `
        }else{

            html += `
            <tr>
                <td>${v.BatchNo}</td>
                <td>${v.FormulaID}</td>
                <td>${v.BatchWeight}</td>
                <td>${v.SchStartDate}</td>
            </tr>
            `

        }

       
      
    });

    return html;
}

function gengettbl(param = {} , paraminfo = {}){
    html = '';
    var x = [];

    $.each(param,function(k,v){

        batchopt = "<a class='btn btn-outline-danger btn-remove'><i class='fas fa-trash'></i></a>";
        batchno = v.batchno;
        formulaid = paraminfo.formulaid;
        batchwt = xres.batchsize;
                        
        mydata.push([batchopt,batchno,formulaid,batchwt]);
                    
        dtax.rows.add(mydata).draw(false);
       
      
    });
}


$(document).on("click",".btn-edit-tbl_runhrd_partial",function(){
    var vdata=$(this).data('id');

    $.post('CreateRunPartial/getRunInfo',{id:vdata},function(xres){
    
        var dbox = bootbox.dialog({
            title : 'Update Run',
            size : 'lg',
            message : `
                <table class='table table-sm table-bordered table-hovered'>
                    <tr>
                        <!--<td>Run No:</td><td><input class='form-control form-control-sm' readonly id='runno'/></td>-->
                        <td>Formula ID:</td><td><input class='form-control form-control-sm' readonly id='formulaid'/></td>
                        <td>Batch Size:</td><td><input class='form-control form-control-sm' readonly id='batchsize'/></td>
                    </tr>
                    <tr>
                        <td>Total Batches:</td><td><input class='form-control form-control-sm' readonly id='total_batch'/></td>
                        <td>Total Batches Size:</td><td><input class='form-control form-control-sm' readonly id='total_batch_size'/></td>
                    </tr>
                    <tr>
                        <td colspan='4'><a class='btn btn-outline-success btn-lg ' id='btn-search' data-action = 'edit'><i class='fas fa-search'></i>&nbsp;Batch Search</a></span>
                    </tr>
                </table>
                <input type='hidden' id='erunno' />
                <table class='table table-bordered table-hovered table-sm' id='nbllist'>
                    <thead class='text-center'>
                        <th></th>
                        <th>Batch No</th>
                        <th>Formula ID</th>
                        <th>Batch Weight</th>
                    </thead>
                    <tbody id='tblbatchlist'>
                    </tbody>
                </table>
            `,
    
            buttons : {
                Submit: {
                    label: "<i class='fas fa-save'></i> &nbsp;SAVE",
                    className: 'btn-outline-success',
                    callback: function(){
                        formulaid = $("#formulaid").val();
                        batchwt = $("#batchsize").val();
                        batchlist = [];
                        erunno
                        $.each(dtax.rows().data(),function(k,v){
                            batchlist.push({
                                'batchno' : v[1],
                                'formulaid' : v[2],
                                'batchwt' : v[3]
                            });
                        });
    
                        var formData = new FormData($('#autoFrm')[0]);
                        formData.append('formulaid',formulaid);
                        formData.append('batchwt',batchwt);
                        formData.append('batchlist',JSON.stringify(batchlist));
                        formData.append('type','U');
                        formData.append('runid', xres.runhdr.runid);
                        formData.append('runno', xres.runhdr.runno);
    
                        $.ajax({
                            type: "POST",
                            url: 'CreateRunPartial/saverun',
                            data: formData,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                
                            },
                            success: function(msg) {
                                var mymsg = $.parseJSON(msg);
    
                               if(mymsg.msgno != undefined){
    
                                    Swal.fire(
                                        mymsg.msg,
                                        '',
                                        (mymsg.msgno == 100 ? 'success' : 'warning' )
                                    ).then((result)=>{
    
                                    });
    
                               }else{
                                    Swal.fire(
                                        'Successfully added',
                                        '',
                                        'success'
                                    ).then((result)=>{
    
    
                                    });
                               }
    
                                APP.Datatable.reDraw(APP.Forms.defaultOpts.frm);
                                
                            },
                            error: function(msg) {
                                Swal.fire(
                                    'Oppps Something went wrong, Please contact the system Adminstrator',
                                    '',
                                    'error'
                                );
                            }
                        });
    
                        
                    }
                },
                Cancel: {
                    label: "<i class='fas fa-window-close'></i> &nbsp;CLOSE",
                    className: 'btn-outline-danger ',
                    callback: function(){
                        
                    }
                },
            }
        });
    
        dbox.on('shown.bs.modal', function(e){
            dtax = $("#nbllist").DataTable();
            if(xres.msgno == '200'){
                $("#runno").val(xres.runhdr.runno);
                $("#formulaid").val(xres.runhdr.formulaid);
                $("#batchsize").val(xres.runhdr.batchsize);
                $("#total_batch").val(xres.rundata.length);
                $("#total_batch_size").val(xres.runhdr.batchsize * xres.rundata.length);
                $("#erunno").val(xres.runhdr.runno)
                //gengettbl(xres.rundata, xres.runhdr);
                mydata = [];
                $.each(xres.rundata,function(k,v){
                    batchopt = "<a class='btn btn-outline-danger btn-remove'><i class='fas fa-trash'></i></a>";
                    batchno = v.batchno;
                    formulaid = xres.runhdr.formulaid;
                    batchwt = xres.runhdr.batchsize;
                                    
                    mydata.push([batchopt,batchno,formulaid,batchwt]);
                                
                    
                });
                dtax.rows.add(mydata).draw(false);
            }
        });

    },'json');

  

});

$(document).on('click','.btn-remove',function(){
  dtax.row($(this).parents('tr')).remove();
  dtax.draw(false);
});