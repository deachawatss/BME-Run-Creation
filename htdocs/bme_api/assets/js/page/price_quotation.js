APP.Forms.defaultOpts.frmsize = 'xl';

function getaddress(res = {}){

    $("#default_delivery_address").val(res.address);

}

$(".btn-add-tbl_price_quotation").off();
$(document).off("click",".btn-edit-tbl_price_quotation");

$(document).on('click',".btn-add-tbl_price_quotation",function(res){
    var html = APP.Forms.getForm('add');
    
    html += "<a class='btn mb-1 btn-outline-success btn-add-details'><i class='fas fa-plus'></i> ADD</a>";
    html += "<table class='table table-sm table-bordered' id='detailsdata'>";
        html += "<thead class='text-center'>";
        html += "<th></th>";
        html += "<th>Formula ID</th>";
        html += "<th>Description</th>";
        html += "<th>Indicative Monthly Vol</th>";
        html += "<th>Pack Size</th>";
        html += "<th>Item Type</th>";
        html += "<th>Other Requirement</th>";
        html += "<th>Price</th>";
        html += "<th>Price Reference</th>";
        html += "<th>Remarks</th>";
        html += "</thead>";

        html += "<tbody id=''>";
        html += "</body>"
    html += "</table>";

    var dd = bootbox.dialog({
        message : html,
        size: 'xl',
        buttons:{
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Save",
                className: 'btn-outline-success',
                callback: function(){
                    
                    var validator = $( "#autoFrm" ).validate({
                        errorElement: 'span',
                        highlight: function (element, errorClass, validClass) {
                          $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                          $(element).removeClass('is-invalid');
                        },
                        errorLabelContainer: "#messageBox",
                        errorPlacement: function(error, element) {
                            if( element.parent().parent().is('li') ){
                                error.appendTo( element.parent().parent().parent().parent());
                            }
                            else if(element.parent().is('div')){
                                error.appendTo( element.parent().parent());
                            }
                            else{
                                error.appendTo( element.parent() );
                            }
                            
                        },
                        rules : APP.Forms.defaultOpts.frmValidation,
                        messages : APP.Forms.defaultOpts.frmValidationMsg,
                    });
                    validator.form();
                    
                    if(!validator.valid())
                        return false;
                    
                    var formData = new FormData($('#autoFrm')[0]);
                    formData.append('dataDetails',JSON.stringify(details_tbl.rows().data().toArray()));
                       $.ajax({
                                type: "POST",
                                url: 'price_quotation/addData',
                                data: formData,
                                contentType: false,
                                processData: false,
                                beforeSend: function() {
                                    
                                },
                                success: function(msg) {
                                    Swal.fire(
                                        'Successfully added',
                                        '',
                                        'success'
                                    );

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
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        },
        //className:'trans-form',
        onShow: function(xxxx) {
           

            $('#tsr').select2({
                theme: 'bootstrap4',
                dropdownParent:$('#tsr').parent(),
                data:in_add_tbl_price_quotation[0].ref,
                placeholder: {
                    id: '', // the value of the option
                    text: 'Please Select'
                },
                allowClear: true
                
            });

            sampleData();
           // details_tbl = $('#detailsdata').DataTable();

        }
    })

});

function getFormDetails(param = 0,details = {}){

    switch(param){

        case 0:{
            
            var html = '';
            var xdata = textval(details.other_requirement);
            var adata = [];
            if(xdata != ''){
                adata = xdata.split(",");
                
            }

            html += '<table class="table table-sm table-bordered">';
                html += '<tr>';
                    html += '<td><label for="formula">Formula ID<span style="color:red">*</span></label></td>'; 
                    html += '<td><input value="'+textval(details.formula)+'" required type="text" id="formula" name="formula" class="form-control form-control-sm" placeholder="Formula ID"/></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="description">Description<span style="color:red">*</span></label></td>'; 
                    html += '<td><textarea required type="text" id="description" name="description" class="form-control form-control-sm" placeholder="Description">'+textval(details.description)+'</textarea></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="indicative_monthly_volume">Inductive Monthly Volumn<span style="color:red">*</span></label></td>'; 
                    html += '<td><input value="'+textval(details.indicative_monthly_volume)+'" required type="text" id="indicative_monthly_volume" name="indicative_monthly_volume" class="form-control form-control-sm" placeholder="Inductive Monthly Volumn"/></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="pack_size">Pack Size<span style="color:red">*</span></label></td>'; 
                    html += '<td><input value="'+textval(details.pack_size)+'" required type="number" id="pack_size" name="pack_size" class="form-control form-control-sm" placeholder="Pack size" /></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="item_type">Item Type<span style="color:red">*</span></label></td>'; 
                    html += '<td>';
                    html += '<ul class="checkbox-grid">';
                    html += '<li style="width:unset">';
                    html += '<div class="form-check"><input '+(textval(details.item_type) == 'R' ? 'checked="checked"' : '' )+' type="Radio" clas="form-check-input" name="item_type" value="R" id="item_type_0"> <label class="form-radio-label" for="item_type_0"> Regular</label></div>';
                    html += '</li>';
                    html += '<li style="width:unset">';
                    html += '<div class="form-check"><input '+(textval(details.item_type) == 'R' ? 'checked="checked"' : '' )+' type="Radio" clas="form-check-input" name="item_type" value="L" id="item_type_1"> <label class="form-radio-label" for="item_type_1"> LTO</label></div>';
                    html += '</li>';
                    html += '</ul>';
                    html += '</td>';
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="other_requirements">Other Requirement<span style="color:red">*</span></label></td>';
                    html += '<td>'; 
                    html += '<ul class="checkbox-grid">';
                    html += '<li>';
                    html += '<div class="form-check"><input '+ ($.inArray("H", adata) > -1 ? "checked='checked'" : "")+' type="checkbox" class="form-check-input" name="other_requirement[]" value="H" id="other_requirement_0"><label class="form-check-label" for="other_requirement_0">HALAL</label></div>';
                    html += '</li>';
                    html += '<li>';
                    html += '<div class="form-check"><input '+($.inArray("M", adata) > -1 ? "checked='checked'" : "")+' type="checkbox" class="form-check-input" name="other_requirement[]" value="M" id="other_requirement_1"><label class="form-check-label" for="other_requirement_1">MICRO</label></div>';
                    html += '</li>';
                    html += '</ul>';
                    html += '</td>';
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="remarks">Remarks<span style="color:red">*</span></label></td>'; 
                    html += '<td><textarea required id="remarks" class="form-control form-control-sm" placeholder="Remarks" >'+textval(details.remarks)+'</textarea></td>'; 
                html += '</tr>'; 
            html += '</table>';
            break;
        }

        case 1:{
            

            var html = '';
            var xdata = textval(details.other_requirement);
            var adata = [];
            if(xdata != ''){
                adata = xdata.split(",");
                
            }

            html += '<table class="table table-sm table-bordered">';
                html += '<tr>';
                    html += '<td><label for="formula">Formula ID<span style="color:red">*</span></label></td>'; 
                    html += '<td><input readonly value="'+textval(details.formula)+'" required type="text" id="formula" name="formula" class="form-control form-control-sm" placeholder="Formula ID"/></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="description">Description<span style="color:red">*</span></label></td>'; 
                    html += '<td><textarea readonly required type="text" id="description" name="description" class="form-control form-control-sm" placeholder="Description">'+textval(details.description)+'</textarea></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="indicative_monthly_volume">Inductive Monthly Volumn<span style="color:red">*</span></label></td>'; 
                    html += '<td><input readonly value="'+textval(details.indicative_monthly_volume)+'" required type="text" id="indicative_monthly_volume" name="indicative_monthly_volume" class="form-control form-control-sm" placeholder="Inductive Monthly Volumn"/></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="pack_size">Pack Size<span style="color:red">*</span></label></td>'; 
                    html += '<td><input readonly value="'+textval(details.pack_size)+'" required type="text" id="pack_size" name="pack_size" class="form-control form-control-sm" placeholder="Pack size" /></td>'; 
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="item_type">Item Type<span style="color:red">*</span></label></td>'; 
                    html += '<td>';
                    html += '<ul class="checkbox-grid">';
                    html += '<li style="width:unset">';
                    html += '<div class="form-check"><input disabled '+(textval(details.item_type) == 'R' ? 'checked="checked"' : '' )+' type="Radio" clas="form-check-input" name="item_type" value="R" id="item_type_0"> <label class="form-radio-label" for="item_type_0"> Regular</label></div>';
                    html += '</li>';
                    html += '<li style="width:unset">';
                    html += '<div class="form-check"><input disabled '+(textval(details.item_type) == 'R' ? 'checked="checked"' : '' )+' type="Radio" clas="form-check-input" name="item_type" value="L" id="item_type_1"> <label class="form-radio-label" for="item_type_1"> LTO</label></div>';
                    html += '</li>';
                    html += '</ul>';
                    html += '</td>';
                html += '</tr>'; 

                html += '<tr>';
                    html += '<td><label for="other_requirements">Other Requirement<span style="color:red">*</span></label></td>';
                    html += '<td>'; 
                    html += '<ul class="checkbox-grid">';
                    html += '<li>';
                    html += '<div class="form-check"><input disabled '+ ($.inArray("H", adata) > -1 ? "checked='checked'" : "")+' type="checkbox" class="form-check-input" name="other_requirement[]" value="H" id="other_requirement_0"><label class="form-check-label" for="other_requirement_0">HALAL</label></div>';
                    html += '</li>';
                    html += '<li>';
                    html += '<div class="form-check"><input  disabled '+($.inArray("M", adata) > -1 ? "checked='checked'" : "")+' type="checkbox" class="form-check-input" name="other_requirement[]" value="M" id="other_requirement_1"><label class="form-check-label" for="other_requirement_1">MICRO</label></div>';
                    html += '</li>';
                    html += '</ul>';
                    html += '</td>';
                html += '</tr>'; 

                html += '<tr>';
                html += '<td><label for="price">Price<span style="color:red">*</span></label></td>'; 
                html += '<td><input required type="number"  value="'+textval(details.price_reference)+'" id="price" name="price" class="form-control form-control-sm" placeholder="Price" /></td>'; 
                html += '</tr>';

                html += '<tr>';
                html += '<td><label for="price_reference">Price Reference<span style="color:red">*</span></label></td>'; 
                html += '<td><input required type="text"  value="'+textval(details.price_reference)+'" id="price_reference" name="price_reference" class="form-control form-control-sm" placeholder="Price Reference" /></td>'; 
                html += '</tr>';

                html += '<tr>';
                    html += '<td><label for="remarks">Remarks<span style="color:red">*</span></label></td>'; 
                    html += '<td><textarea readonly required id="remarks" class="form-control form-control-sm" placeholder="Remarks" >'+textval(details.remarks)+'</textarea></td>'; 
                html += '</tr>'; 
            html += '</table>';
        }

    }

    return html;

}

$(document).on('click','.btn-add-details',function(){
    var html = getFormDetails();
    var dd = bootbox.dialog({
        title: 'Add Price Details',
        message : "<form id='autoFrmDetails'>"+html+'</form>',
        size: 'lg',
        buttons:{
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Save",
                className: 'btn-outline-success',
                callback: function(){
                    
                    var validator = $( "#autoFrmDetails" ).validate({
                        errorElement: 'span',
                        highlight: function (element, errorClass, validClass) {
                          $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                          $(element).removeClass('is-invalid');
                        },
                        errorLabelContainer: "#messageBox",
                        errorPlacement: function(error, element) {
                            if( element.parent().parent().is('li') ){
                                error.appendTo( element.parent().parent().parent().parent());
                            }
                            else if(element.parent().is('div')){
                                error.appendTo( element.parent().parent());
                            }
                            else{
                                error.appendTo( element.parent() );
                            }
                            
                        },
                        rules : APP.Forms.defaultOpts.frmValidation,
                        messages : APP.Forms.defaultOpts.frmValidationMsg,
                    });
                    validator.form();
                    
                    if(!validator.valid())
                        return false;
                    
                    var formData = new FormData($('#autoFrmDetails')[0]);
                    var object = {
                        price_quotation_details_id:'',
                        formula:$("#formula").val(),
                        description:$("#description").val(),
                        indicative_monthly_volume:$("#indicative_monthly_volume").val(),
                        pack_size:$("#pack_size").val(),
                        item_type:$("[name='item_type']:checked").val(),
                        other_requirement:Array.from($(".form-check-input:checked"), a => a.value).toString(),
                        price:'',
                        price_reference:'',
                        remarks:$("#remarks").val(),
                    };
                    //object = Object.fromEntries(formData);
                    //object.price_quotation_details_id = '';
                    details_tbl.row.add(object).draw();
                    
                }
            
            },
            Cancel: {
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        },
        //className:'trans-form',
        onShow: function(xxxx) {
            

            $('#tsr').select2({
                theme: 'bootstrap4',
                dropdownParent:$('#tsr').parent(),
                data:in_add_tbl_price_quotation[0].ref,
                placeholder: {
                    id: '', // the value of the option
                    text: 'Please Select'
                },
                allowClear: true
                
            });

        }
    });
});

$(document).on('click','#detailsdata tbody .btn-sampleinfo-trash',function(){
    details_tbl.row( $(this).parent().parent() ).remove().draw();
});

$(document).on('click','#detailsdata tbody .btn-sampleinfo-edit',function(){
    var thisSelector = $(this).parent().parent();
    var data = details_tbl.row(thisSelector).data();
    
    var html = getFormDetails(0,data);
    var dd = bootbox.dialog({
        title: 'Add Price Details',
        message : "<form id='autoFrmDetails'>"+html+'</form>',
        size: 'lg',
        buttons:{
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Save",
                className: 'btn-outline-success',
                callback: function(){
                    
                    var validator = $( "#autoFrmDetails" ).validate({
                        errorElement: 'span',
                        highlight: function (element, errorClass, validClass) {
                          $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                          $(element).removeClass('is-invalid');
                        },
                        errorLabelContainer: "#messageBox",
                        errorPlacement: function(error, element) {
                            if( element.parent().parent().is('li') ){
                                error.appendTo( element.parent().parent().parent().parent());
                            }
                            else if(element.parent().is('div')){
                                error.appendTo( element.parent().parent());
                            }
                            else{
                                error.appendTo( element.parent() );
                            }
                            
                        },
                        rules : APP.Forms.defaultOpts.frmValidation,
                        messages : APP.Forms.defaultOpts.frmValidationMsg,
                    });
                    validator.form();
                    
                    if(!validator.valid())
                        return false;
                    
                    var formData = new FormData($('#autoFrmDetails')[0]);
                    var object = {
                        price_quotation_details_id:'',
                        formula:$("#formula").val(),
                        description:$("#description").val(),
                        indicative_monthly_volume:$("#indicative_monthly_volume").val(),
                        pack_size:$("#pack_size").val(),
                        item_type:$("[name='item_type']:checked").val(),
                        other_requirement:Array.from($(".form-check-input:checked"), a => a.value).toString(),
                        price:'',
                        price_reference:'',
                        remarks:$("#remarks").val(),
                    };
                    //object = Object.fromEntries(formData);
                    //object.price_quotation_details_id = '';
                    //details_tbl.row.add(object).draw();
                    details_tbl.row(thisSelector).data(object).draw();
                    
                }
            
            },
            Cancel: {
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        },
        //className:'trans-form',
        onShow: function(xxxx) {
            

            $('#tsr').select2({
                theme: 'bootstrap4',
                dropdownParent:$('#tsr').parent(),
                data:in_add_tbl_price_quotation[0].ref,
                placeholder: {
                    id: '', // the value of the option
                    text: 'Please Select'
                },
                allowClear: true
                
            });

        }
    });
});

$(document).on('click',".btn-edit-tbl_price_quotation",function(res){
    var mydata = $(this).data('id');
    var html = APP.Forms.getForm('edit');
    
    html += "<a class='btn mb-1 btn-outline-success btn-add-details'><i class='fas fa-plus'></i> ADD</a>";
    html += "<table class='table table-sm table-bordered' id='detailsdata'>";
        html += "<thead class='text-center'>";
        html += "<th></th>";
        html += "<th>Formula ID</th>";
        html += "<th>Description</th>";
        html += "<th>Indicative Monthly Vol</th>";
        html += "<th>Pack Size</th>";
        html += "<th>Item Type</th>";
        html += "<th>Other Requirement</th>";
        html += "<th>Price</th>";
        html += "<th>Price Reference</th>";
        html += "<th>Remarks</th>";
        html += "</thead>";

        html += "<tbody id=''>";
        html += "</body>"
    html += "</table>";

    var dd = bootbox.dialog({
        message : html,
        size: 'xl',
        buttons:{
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Save",
                className: 'btn-outline-success',
                callback: function(){
                    
                    var validator = $( "#autoFrm" ).validate({
                        errorElement: 'span',
                        highlight: function (element, errorClass, validClass) {
                          $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                          $(element).removeClass('is-invalid');
                        },
                        errorLabelContainer: "#messageBox",
                        errorPlacement: function(error, element) {
                            if( element.parent().parent().is('li') ){
                                error.appendTo( element.parent().parent().parent().parent());
                            }
                            else if(element.parent().is('div')){
                                error.appendTo( element.parent().parent());
                            }
                            else{
                                error.appendTo( element.parent() );
                            }
                            
                        },
                        rules : APP.Forms.defaultOpts.frmValidation,
                        messages : APP.Forms.defaultOpts.frmValidationMsg,
                    });
                    validator.form();
                    
                    if(!validator.valid())
                        return false;
                    
                    var formData = new FormData($('#autoFrm')[0]);
                    formData.append('dataDetails',JSON.stringify(details_tbl.rows().data().toArray()));
                    formData.append('price_quotation_id',mydata);
                       $.ajax({
                                type: "POST",
                                url: 'price_quotation/updateData',
                                data: formData,
                                contentType: false,
                                processData: false,
                                beforeSend: function() {
                                    
                                },
                                success: function(msg) {
                                    Swal.fire(
                                        'Successfully added',
                                        '',
                                        'success'
                                    );

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
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        },
        //className:'trans-form',
        onShow: function(xxxx) {
            sampleData(mydata);

            $('#tsr').select2({
                theme: 'bootstrap4',
                dropdownParent:$('#tsr').parent(),
                data:in_add_tbl_price_quotation[0].ref,
                placeholder: {
                    id: '', // the value of the option
                    text: 'Please Select'
                },
                allowClear: true
                
            });

            $.post('price_quotation/getInfo',{id:mydata},function(res){
                $("#project").val(res.data.project);
                $("#default_delivery_address").val(res.data.default_delivery_address);
                $("#actual_delivery_address").val(res.data.actual_delivery_address);

                $("#tsr").val(res.data.tsr);
                $("#tsr").trigger('change.select2');
                $("#pop_customer").val(res.pop_updata.customer);
                $("#customer").val(res.data.customer);
            },'json');

        }
    })

});

$(document).on('click',".btn-trans-tbl_price_quotation",function(res){
    var mydata = $(this).data('id');
    var html = APP.Forms.getForm('edit');
    
    //html += "<a class='btn mb-1 btn-outline-success btn-add-details'><i class='fas fa-plus'></i> ADD</a>";
    html += "<table class='table table-sm table-bordered' id='detailsdata'>";
        html += "<thead class='text-center'>";
        html += "<th></th>";
        html += "<th>Formula ID</th>";
        html += "<th>Description</th>";
        html += "<th>Indicative Monthly Vol</th>";
        html += "<th>Pack Size</th>";
        html += "<th>Item Type</th>";
        html += "<th>Other Requirement</th>";
        html += "<th>Price</th>";
        html += "<th>Price Reference</th>";
        html += "<th>Remarks</th>";
        html += "</thead>";

        html += "<tbody id=''>";
        html += "</body>"
    html += "</table>";

    var dd = bootbox.dialog({
        message : html,
        size: 'xl',
        buttons:{
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Save",
                className: 'btn-outline-success',
                callback: function(){
                    
                    var validator = $( "#autoFrm" ).validate({
                        errorElement: 'span',
                        highlight: function (element, errorClass, validClass) {
                          $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                          $(element).removeClass('is-invalid');
                        },
                        errorLabelContainer: "#messageBox",
                        errorPlacement: function(error, element) {
                            if( element.parent().parent().is('li') ){
                                error.appendTo( element.parent().parent().parent().parent());
                            }
                            else if(element.parent().is('div')){
                                error.appendTo( element.parent().parent());
                            }
                            else{
                                error.appendTo( element.parent() );
                            }
                            
                        },
                        rules : APP.Forms.defaultOpts.frmValidation,
                        messages : APP.Forms.defaultOpts.frmValidationMsg,
                    });
                    validator.form();
                    
                    if(!validator.valid())
                        return false;
                    
                    var formData = new FormData($('#autoFrm')[0]);
                    formData.append('dataDetails',JSON.stringify(details_tbl.rows().data().toArray()));
                    formData.append('price_quotation_id',mydata);
                       $.ajax({
                                type: "POST",
                                url: 'price_quotation/updateData',
                                data: formData,
                                contentType: false,
                                processData: false,
                                beforeSend: function() {
                                    
                                },
                                success: function(msg) {
                                    Swal.fire(
                                        'Successfully added',
                                        '',
                                        'success'
                                    );

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
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        },
        //className:'trans-form',
        onShow: function(xxxx) {
            sampleData(mydata);

            $('#tsr').select2({
                theme: 'bootstrap4',
                dropdownParent:$('#tsr').parent(),
                data:in_add_tbl_price_quotation[0].ref,
                placeholder: {
                    id: '', // the value of the option
                    text: 'Please Select'
                },
                allowClear: true
                
            });

            $.post('price_quotation/getInfo',{id:mydata},function(res){
                $("#project").val(res.data.project);
                $("#default_delivery_address").val(res.data.default_delivery_address);
                $("#actual_delivery_address").val(res.data.actual_delivery_address);

                $("#tsr").val(res.data.tsr);
                $("#tsr").trigger('change.select2');
                $("#pop_customer").val(res.pop_updata.customer);
                $("#customer").val(res.data.customer);

                $("#project").attr('disabled','disabled');
                $("#default_delivery_address").attr('disabled','disabled');
                $("#actual_delivery_address").attr('disabled','disabled');

                $("#tsr").attr('disabled','disabled');
                $("#pop-upcustomer").hide();
                $("#pop_customer").attr('disabled','disabled');
                $("#customer").attr('disabled','disabled');

            },'json');

        }
    })

});

$(document).on('click','#detailsdata tbody .btn-sampleinfo-trans',function(){
    var thisSelector = $(this).parent().parent();
    var data = details_tbl.row(thisSelector).data();

    var html = getFormDetails(1,data);
    var dd = bootbox.dialog({
        title: 'Add Price Details',
        message : "<form id='autoFrmDetails'>"+html+'</form>',
        size: 'lg',
        buttons:{
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Save",
                className: 'btn-outline-success',
                callback: function(){
                    
                    var validator = $( "#autoFrmDetails" ).validate({
                        errorElement: 'span',
                        highlight: function (element, errorClass, validClass) {
                          $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                          $(element).removeClass('is-invalid');
                        },
                        errorLabelContainer: "#messageBox",
                        errorPlacement: function(error, element) {
                            if( element.parent().parent().is('li') ){
                                error.appendTo( element.parent().parent().parent().parent());
                            }
                            else if(element.parent().is('div')){
                                error.appendTo( element.parent().parent());
                            }
                            else{
                                error.appendTo( element.parent() );
                            }
                            
                        },
                        rules : APP.Forms.defaultOpts.frmValidation,
                        messages : APP.Forms.defaultOpts.frmValidationMsg,
                    });
                    validator.form();
                    
                    if(!validator.valid())
                        return false;
                    
                    var formData = new FormData($('#autoFrmDetails')[0]);
                    var object = {
                        price_quotation_details_id:'',
                        formula:$("#formula").val(),
                        description:$("#description").val(),
                        indicative_monthly_volume:$("#indicative_monthly_volume").val(),
                        pack_size:$("#pack_size").val(),
                        item_type:$("[name='item_type']:checked").val(),
                        other_requirement:Array.from($(".form-check-input:checked"), a => a.value).toString(),
                        price:$('#price').val(),
                        price_reference:$('#price_reference').val(),
                        remarks:$("#remarks").val(),
                    };
                    //object = Object.fromEntries(formData);
                    //object.price_quotation_details_id = '';
                    //details_tbl.row.add(object).draw();
                    details_tbl.row(thisSelector).data(object).draw();
                }
            
            },
            Cancel: {
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        },
        //className:'trans-form',
        onShow: function(xxxx) {
            

            $('#tsr').select2({
                theme: 'bootstrap4',
                dropdownParent:$('#tsr').parent(),
                data:in_add_tbl_price_quotation[0].ref,
                placeholder: {
                    id: '', // the value of the option
                    text: 'Please Select'
                },
                allowClear: true
                
            });

        }
    });
});

$(document).on('click',".btn-print-tbl_price_quotation",function(){
    var mydata = $(this).data('id');
    html = '<table class="table table-sm table-bordered">';
        html += '<tr>';
            html += '<td>Effectivity Date</td>';
            html += '<td><input type="date" id="efd"/></td>';
        html += '</tr>';

        html += '<tr>';
            html += '<td>Quotation valid until</td>';
            html += '<td><input type="date" id="qvu"/></td>';
        html += '</tr>';
    html += '</table>';
    bootbox.dialog({
        message : html,
        buttons : {
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Print",
                className: 'btn-outline-success',
                callback: function(){
                    window.open('price_quotation/printPriceQuotation?id='+mydata+"&efd="+$("#efd").val()+"&qvu="+$("#qvu").val(),"_blank");
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

$(document).ready(function(){

    if(srid != null){

        var html = APP.Forms.getForm('add');
    
    html += "<a class='btn mb-1 btn-outline-success btn-add-details'><i class='fas fa-plus'></i> ADD</a>";
    html += "<table class='table table-sm table-bordered' id='detailsdata'>";
        html += "<thead class='text-center'>";
        html += "<th></th>";
        html += "<th>Formula ID</th>";
        html += "<th>Description</th>";
        html += "<th>Indicative Monthly Vol</th>";
        html += "<th>Pack Size</th>";
        html += "<th>Item Type</th>";
        html += "<th>Other Requirement</th>";
        html += "<th>Price</th>";
        html += "<th>Price Reference</th>";
        html += "<th>Remarks</th>";
        html += "</thead>";

        html += "<tbody id=''>";
        html += "</body>"
    html += "</table>";

    var dd = bootbox.dialog({
        message : html,
        size: 'xl',
        buttons:{
            Submit: {
                label: "<i class='fas fa-save'></i> &nbsp;Save",
                className: 'btn-outline-success',
                callback: function(){
                    
                    var validator = $( "#autoFrm" ).validate({
                        errorElement: 'span',
                        highlight: function (element, errorClass, validClass) {
                          $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                          $(element).removeClass('is-invalid');
                        },
                        errorLabelContainer: "#messageBox",
                        errorPlacement: function(error, element) {
                            if( element.parent().parent().is('li') ){
                                error.appendTo( element.parent().parent().parent().parent());
                            }
                            else if(element.parent().is('div')){
                                error.appendTo( element.parent().parent());
                            }
                            else{
                                error.appendTo( element.parent() );
                            }
                            
                        },
                        rules : APP.Forms.defaultOpts.frmValidation,
                        messages : APP.Forms.defaultOpts.frmValidationMsg,
                    });
                    validator.form();
                    
                    if(!validator.valid())
                        return false;
                    
                    var formData = new FormData($('#autoFrm')[0]);
                    formData.append('dataDetails',JSON.stringify(details_tbl.rows().data().toArray()));
                    formData.append('srmid',srid.srm_id);
                       $.ajax({
                                type: "POST",
                                url: 'price_quotation/addData',
                                data: formData,
                                contentType: false,
                                processData: false,
                                beforeSend: function() {
                                    
                                },
                                success: function(msg) {
                                    Swal.fire(
                                        'Successfully added',
                                        '',
                                        'success'
                                    );

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
                label: "<i class='fas fa-window-close'></i> &nbsp;Cancel",
                className: 'btn-outline-danger ',
                callback: function(){
                    
                }
            },
        },
        //className:'trans-form',
        onShow: function(xxxx) {
           

            $('#tsr').select2({
                theme: 'bootstrap4',
                dropdownParent:$('#tsr').parent(),
                data:in_add_tbl_price_quotation[0].ref,
                placeholder: {
                    id: '', // the value of the option
                    text: 'Please Select'
                },
                allowClear: true
                
            });

            sampleData();
            
            $("#tsr").val(srid.TSR);
            $("#tsr").trigger('change.select2');
            $("#pop_customer").val(arcust.Customer_Name);
            $("#customer").val(arcust.Customer_Key);
            $("#default_delivery_address").val(arcust.address);
            $("#project").val(srid.Project);

           // details_tbl = $('#detailsdata').DataTable();

        }
    })

    }
    

});