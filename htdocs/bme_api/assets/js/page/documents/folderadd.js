$(document).on('click','#exp-new-folder',function(){
    contextMenu.style.display = 'none';
    var prompt = bootbox.dialog({
        'title' : 'New Folder',
        'message' : '<table class="table table-sm table-bordered">'+
                    '<tr><td>Folder Name</td><td><input type="text" class="form-control form-control-sm" id="foldername"/></td></tr>'+
                    '<tr><td>View User Permission</td><td><select multiple="multiple" class="form-control form-control-sm" id="view_user_permission" name="view_user_permission[]"></select></td></tr>'+
                    '<tr><td>View Group Permission</td><td><select multiple="multiple" class="form-control  form-control-sm" id="view_group_permission" name="view_group_permission[]"></select></td></tr>'+
                    '<tr><td>Upload User Permission</td><td><select multiple="multiple" class="form-control form-control-sm" id="upload_user_permission" name="upload_user_permission[]"></select></td></tr>'+
                    '<tr><td>Upload Group Permission</td><td><select multiple="multiple" class="form-control  form-control-sm" id="upload_group_permission" name="upload_group_permission[]"></select></td></tr>'+
                    '<tr><td>Delete User Permission</td><td><select multiple="multiple" class="form-control form-control-sm" id="delete_user_permission"  name="delete_user_permission[]"></select></td></tr>'+
                    '<tr><td>Delete Group Permission</td><td><select multiple="multiple" class="form-control  form-control-sm" id="delete_group_permission" name="delete_group_permission[]"></select></td></tr>'+
                    '</table>',
        'buttons':{
            Save: {
                label: '<i class="fas fa-save"></i>&nbsp;Save',
                className: 'btn-outline-success ',
                callback: function(){
                    $.post('documents/addfolder',{
                            foldername : $("#foldername").val(), 
                            view_user_permission : $("#view_user_permission").val(), 
                            upload_user_permission : $("#upload_user_permission").val(), 
                            delete_user_permission : $("#delete_user_permission").val(), 
                            view_group_permission : $("#view_group_permission").val(), 
                            upload_group_permission : $("#upload_group_permission").val(), 
                            delete_group_permission : $("#delete_group_permission").val(), 
                            current_menu : current_menu, 
                        },function(res){
                            if(res.msgcode != 1){
                                Swal.fire(
                                    errorcode[res.msgcode],
                                    '',
                                    'error'
                                  );
                            }
                            getfolder();

                    },"json");         
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
            theme: "bootstrap4"
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