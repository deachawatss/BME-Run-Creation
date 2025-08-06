$(document).on('click','#exp-new-file',function(){
    contextMenu.style.display = 'none';
    var prompt = bootbox.dialog({
        'title' : 'Upload File',
        'message' : '<form id="formFile">'+
                    '<div><input required type="file" name="fileupload" class="form-control form-control-sm mb-3" id="fileupload" style="height:unset" accept="application/pdf"/></div>'+
                    '<ul class="nav nav-tabs" id="frmtab" role="tablist">'+
                        '<li class="nav-item">'+
                            '<a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Information</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                            '<a class="nav-link" id="permission-tab" data-toggle="tab" href="#permission" role="tab" aria-controls="permission" aria-selected="false">Permission</a>'+
                        '</li>'+
                    '</ul>'+
                    '<div class="tab-content" id="frmtabContent">'+
                        '<div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">'+
                            '<table class="table table-sm table-bordered">'+
                                '<tr><td style="width: 200px">Document Name<span style="color:red">*</span></td><td><input required type="text" class="form-control form-control-sm" name="doc_title" id="doc_title"/></td></tr>'+
                                '<tr><td style="width: 200px">Description<span style="color:red">*</span></td><td><textarea required class="form-control form-control-sm" name="description" id="description"></textarea></td></tr>'+
                                '<tr><td style="width: 200px">Tags</td><td><select  multiple="multiple" class="form-control form-control-sm" id="tags" name="tags[]"></select></td></tr>'+
                                '<tr><td style="width: 200px">Type<span style="color:red">*</span></td><td><select class="form-control form-control-sm" id="type" name="doc_type"></select></td></tr>'+
                                '<tr><td style="width: 200px">Department<span style="color:red">*</span></td><td><select required class="form-control form-control-sm" name="dept" id="dept"></select></td></tr>'+
                                '<tr><td style="width: 200px">Box</td><td><select class="form-control form-control-sm" name="box_id" id="box_id"></select></td></tr>'+
                                '<tr><td style="width: 200px">Retention (Yrs)<span style="color:red">*</span></td><td><input required type="number" class="form-control form-control-sm" name="retention" id="retention"/></td></tr>'+
                            '</table>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="permission" role="tabpanel" aria-labelledby="permission-tab">'+
                            '<table class="table table-sm table-bordered">'+
                                '<tr><td style="width: 200px">View User Permission</td><td><select  multiple="multiple" class="form-control form-control-sm" id="view_user_permission" name="view_user_permission[]"></select></td></tr>'+
                                '<tr><td>View Group Permission</td><td><select multiple="multiple" class="form-control  form-control-sm" id="view_group_permission" name="view_group_permission[]"></select></td></tr>'+
                                '<tr><td>Upload User Permission</td><td><select multiple="multiple" class="form-control form-control-sm" id="upload_user_permission" name="upload_user_permission[]"></select></td></tr>'+
                                '<tr><td>Upload Group Permission</td><td><select multiple="multiple" class="form-control  form-control-sm" id="upload_group_permission" name="upload_group_permission[]"></select></td></tr>'+
                                '<tr><td>Delete User Permission</td><td><select multiple="multiple" class="form-control form-control-sm" id="delete_user_permission"  name="delete_user_permission[]"></select></td></tr>'+
                                '<tr><td>Delete Group Permission</td><td><select multiple="multiple" class="form-control  form-control-sm" id="delete_group_permission" name="delete_group_permission[]"></select></td></tr>'+
                            '</table>'+
                        '</div>'+
                    '</div>'+
                    '</form>',
        'buttons':{
            Save: {
                label: '<i class="fas fa-save"></i>&nbsp;Save',
                className: 'btn-outline-success ',
                callback: function(){

                    var validator = $( "#formFile" ).validate({
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
                        rules : [],
                        messages : [],
                    });

                    validator.form();
                                        
                    if(!validator.valid())
                        return false;
                    var formData = new FormData($('#formFile')[0]);
                    formData.append('current_menu',current_menu);
                    $.ajax({
                        url: 'documents/addfile', 
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(msg) {
                            msg = $.parseJSON(msg);
                            if(msg.msgcode != 1){
                                Swal.fire(
                                    errorcode[msg.msgcode],
                                    '',
                                    'error'
                                  );
                            }else{

                                /*
                                Swal.fire({
                                    //'<img src="data:image/png;base64,'+msg.barcode+'/>',
                                    imageUrl:'data:image/png;base64,'+msg.barcode,
                                    
                                });
                                */
                               var mmhtml = "<style>table, td, th{border: 1px solid black;} td, th{width: 160px}</style>";
                                    mmhtml += '<div>';
                                    mmhtml += '<table style="border-collapse: collapse;">';
                                    
                                    mmhtml += '<tr>';
                                    mmhtml += '<td colspan=4 style="text-align:center"><h3>NWFTH DOCUMENT MANAGEMENT SYSTEM</h3></td>';
                                    mmhtml += '</tr>';
                                    mmhtml += '<tr>';
                                    mmhtml += '<td colspan=4 style="text-align:center"><img src="data:image/png;base64,'+msg.barcode+'" /></td>';
                                    mmhtml += '</tr>';


                                    mmhtml += '<tr>';
                                    mmhtml += '<td style="width:160px">Document No.:</td>';
                                    mmhtml += '<td colspan=3>'+msg.docno+'</td>';
                                    mmhtml += '</tr>';

                                    mmhtml += '<tr>';
                                    mmhtml += '<td>Document Name:</td>';
                                    mmhtml += '<td colspan=3>'+ $('#doc_title').val() +'</td>';
                                    mmhtml += '</tr>';

                                    mmhtml += '<tr>';
                                    mmhtml += '<td>Document Description:</td>';
                                    mmhtml += '<td colspan=3>'+ $("#description").val() +'</td>';
                                    mmhtml += '</tr>';

                                    mmhtml += '<tr>';
                                    mmhtml += '<td>Document Type:</td>';
                                    mmhtml += '<td>'+ $("#type").select2('data').text +'</td>';
                                    mmhtml += '<td>Department:</td>';
                                    mmhtml += '<td>'+ $("#dept").select2('data').text +'</td>';
                                    mmhtml += '</tr>';

                                    mmhtml += '<tr>';
                                    mmhtml += '<td>Date of Entry:</td>';
                                    mmhtml += '<td></td>';
                                    mmhtml += '<td>Enter By:</td>';
                                    mmhtml += '<td></td>';
                                    mmhtml += '</tr>';
                                    
                                    mmhtml += '<tr>';
                                    mmhtml += '<td>Date Certified:</td>';
                                    mmhtml += '<td></td>';
                                    mmhtml += '<td>Certified By:</td>';
                                    mmhtml += '<td></td>';
                                    mmhtml += '</tr>';

                                    mmhtml += '<tr>';
                                    mmhtml += '<td>Date Verified:</td>';
                                    mmhtml += '<td></td>';
                                    mmhtml += '<td>Verified By:</td>';
                                    mmhtml += '<td></td>';
                                    mmhtml += '</tr>';
                                    
                                    mmhtml += '</table>';
                                   mmhtml +='</div>';

                                printCrossword(mmhtml);
                            }

                            getfolder();
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
                label: '<i class="fas fa-closed"></i>&nbsp;Cancel',
                className: 'btn-outline-danger ',
                callback: function(){
                                    
                }
            },
        }
    });

    prompt.on('shown.bs.modal', function(e){
        $("#view_user_permission").select2({
            placeholder: 'User List',
            allowClear: true,
            data:userlist,
            theme: "bootstrap4",
        });

        $("#upload_user_permission").select2({
            placeholder: 'User List',
            allowClear: true,
            data:userlist,
            theme: "bootstrap4"
        });

        $("#delete_user_permission").select2({
            placeholder: 'User List',
            allowClear: true,
            data:userlist,
            theme: "bootstrap4"
        });

        $("#view_group_permission").select2({
            placeholder: 'Group User List',
            allowClear: true,
            data:usergrouplist,
            theme: "bootstrap4"
        });

        $("#upload_group_permission").select2({
            placeholder: 'Group User List',
            allowClear: true,
            data:usergrouplist,
            theme: "bootstrap4"
        });

        $("#delete_group_permission").select2({
            placeholder: 'Group User List',
            allowClear: true,
            data:usergrouplist,
            theme: "bootstrap4"
        });

        $("#type").select2({
            placeholder: 'Please Select',
            allowClear: true,
            data:typelist,
            theme: "bootstrap4",
        });

        $("#box_id").select2({
            placeholder: 'Please select',
            allowClear: true,
            data:boxlist,
            theme: "bootstrap4",
        });

        $("#tags").select2({
            placeholder: 'Enter Tags',
            tags: true,
            theme: "bootstrap4",
        });

        $("#dept").select2({
            placeholder: 'Please select',
            allowClear: true,
            data:deptlist,
            theme: "bootstrap4",
        });

        if( folderpermission.view_permission != null && Array.isArray($.parseJSON(folderpermission.view_permission) ) ){
            $("#view_user_permission").val($.parseJSON(folderpermission.view_permission)).trigger('change.select2');
        }else{
            $("#view_user_permission").val(folderpermission.view_permission).trigger('change.select2');
        }

        if(folderpermission.upload_permission != null && Array.isArray($.parseJSON(folderpermission.upload_permission) ) ){
            $("#upload_user_permission").val($.parseJSON(folderpermission.upload_permission)).trigger('change.select2');
        }else{
            $("#upload_user_permission").val(folderpermission.upload_permission).trigger('change.select2');
        }

        if(folderpermission.delete_permission != null && Array.isArray($.parseJSON(folderpermission.delete_permission) ) ){
            $("#delete_user_permission").val($.parseJSON(folderpermission.delete_permission)).trigger('change.select2');
        }else{
            $("#delete_user_permission").val(folderpermission.delete_permission).trigger('change.select2');
        }

        if(folderpermission.view_group_permission != null && Array.isArray($.parseJSON(folderpermission.view_group_permission) ) ){
            $("#view_group_permission").val($.parseJSON(folderpermission.view_group_permission)).trigger('change.select2');
        }else{
            $("#view_group_permission").val(folderpermission.view_group_permission).trigger('change.select2');
        }

        if(folderpermission.upload_group_permission != null && Array.isArray($.parseJSON(folderpermission.upload_group_permission) ) ){
            $("#upload_group_permission").val($.parseJSON(folderpermission.upload_group_permission)).trigger('change.select2');
        }else{
            $("#upload_group_permission").val(folderpermission.upload_group_permission).trigger('change.select2');
        }

        if(folderpermission.delete_group_permission != null && Array.isArray($.parseJSON(folderpermission.delete_group_permission) ) ){
            $("#delete_group_permission").val($.parseJSON(folderpermission.delete_group_permission)).trigger('change.select2');
        }else{
            $("#delete_group_permission").val(folderpermission.delete_group_permission).trigger('change.select2');
        }
        
    });
});