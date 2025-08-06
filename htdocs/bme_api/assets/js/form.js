/**
* APP - Forms
* - Generic listeners related to user inputs/forms
*/
var APP = APP || {};
var _ALLFIELDS = [];
$.fn.modal.Constructor.prototype._enforceFocus = function() {};
(function () {
    APP.Forms = {
        defaultOpts: {
            rules: null,
            messages: null,
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            normalizer: function(value) {
                // Extra validation that trims the value of every element
                return $.trim(value);
            },
            frmTitle : null,
            frmTemplate : 'default',
            frmButton: false,

            frmAddHtml: null,
            frmAddFields: {},
            frmAddValue: {},
            frmAddAction: false,

            frmEditHtml: null,
            frmEditFields: {},
            frmEditValue: {},
            frmEditAction: false,

            frmCopyHtml: null,
            frmCopyFields: {},
            frmCopyValue: {},
            frmCopyAction: false,

            frmViewHtml: null,
            frmViewFields: {},
            frmViewValue: {},
            frmViewAction: false,
            frm: null,
            frmhtml: null,
            frmsize: null,

            frmValidation : {},
            frmValidationMsg : {},
            frmUseoldjs : false,
            frmCustomSelect2 : {},
            frmFn : function(frmFields=[], frmValues=[]){},

            frmDeleteAction: false,
            selector: null
        },

        init: function () {
            //APP.Forms.trimInputs();
        },
        isSet: function(element){

            if(typeof element != 'undefined'){
        
                return true;
        
            }

            return false;
        },

        getForm:  function(frmMethod="", frmAction=""){
            var msg = "";
            var title = "";
            var frmFields = {};
            var actionBtn = {};
            var toSelect2={};
            var toDisable = [];
            switch(frmMethod){
                case "add": {
                    if( !frmAction ){

                        frmAction = '/addData';
                    }

                    frmAction = (APP.Forms.defaultOpts.frmAddAction != false ? APP.Forms.defaultOpts.frmAddAction : frmAction);


                    if(APP.Forms.defaultOpts.frmTitle == ""){
                        title = "ADD";
                    }else{
                        title = APP.Forms.defaultOpts.frmTitle;
                    }
                    
                    frmhtml = APP.Forms.defaultOpts.frmAddHtml;
                    frmFields = APP.Forms.defaultOpts.frmAddFields;
                    frmValues = APP.Forms.defaultOpts.frmAddValue;
                    

                    break;
                }

                case "edit": {
                    if( !frmAction ){

                        if(!APP.Forms.defaultOpts.frmEditAction)
                           frmAction = '/updateData';
                        else
                            frmAction = APP.Forms.defaultOpts.frmEditAction;

                    }
                    if(APP.Forms.defaultOpts.frmTitle == ""){
                        title = "EDIT";
                    }
                    else{
                        title = APP.Forms.defaultOpts.frmTitle;
                    }
                   
                    frmhtml = APP.Forms.defaultOpts.frmEditHtml;
                    frmFields = APP.Forms.defaultOpts.frmEditFields;
                    frmValues = APP.Forms.defaultOpts.frmEditValue;

                    

                    break;
                }

                case "copy": {
                    if( !frmAction ){
                        if(!APP.Forms.defaultOpts.frmCopyAction)
                           frmAction = '/copyData';
                        else
                            frmAction = APP.Forms.defaultOpts.frmCopyAction;

                    }

                    if(APP.Forms.defaultOpts.frmTitle == ""){
                        title = "COPY";
                    }else{
                        title = APP.Forms.defaultOpts.frmTitle;
                    }
                    frmhtml = APP.Forms.defaultOpts.frmCopyHtml;
                    frmFields = APP.Forms.defaultOpts.frmCopyFields;
                    frmValues = APP.Forms.defaultOpts.frmCopyValue;
                    break;
                }

                case 'view':{

                    if(APP.Forms.defaultOpts.frmTitle == ""){
                        title = "VIEW";
                    }
                    else{
                        title = APP.Forms.defaultOpts.frmTitle;
                    }

                    frmAction='';
                    frmhtml = APP.Forms.defaultOpts.frmViewHtml;
                    frmFields = APP.Forms.defaultOpts.frmViewFields;
                    frmValues = APP.Forms.defaultOpts.frmViewValue;
                    break;
                }
                

                default:{

                    if(APP.Forms.defaultOpts.frmTitle == ""){
                        title = "";
                    }
                    else{
                        title = APP.Forms.defaultOpts.frmTitle;
                    }

                    frmAction='';
                    frmhtml = APP.Forms.defaultOpts.frmhtml;
                    frmValues = APP.Forms.defaultOpts.frmViewValue;
                    frmFields = APP.Forms.defaultOpts.frmViewFields;
                    break;
                }

            }

            msg ="<form id='autoFrm' style='white-space: nowrap'><table class='table table-bordered table-sm'>";
            if(frmhtml == null){
                $.each(frmFields,function(k,v){
                    var valid_ = {};
                    var valid_msg = {};
                    
                    //is Required
                    if(v.req == true || v.req == 1){
                        valid_.required = true;
                        valid_msg.required = "This field is required";
                    }

                    //is number
                    if(v.type == "number"){
                        valid_.number = true;
                        valid_msg.number = "This field is number";
                    }

                    //max length
                    if(v.maxsize != '' && v.maxsize != undefined && v.maxsize != 0){
                        valid_.maxlength = v.maxsize;
                        valid_msg.maxlength = "The maxsize of this field is {0}";
                    }

                    if(Object.keys(valid_).length > 0 ){
                        if( v.type == 'checked'  ){
                            APP.Forms.defaultOpts.frmValidation[v.db + '[]'] = valid_;
                            APP.Forms.defaultOpts.frmValidationMsg[v.db + '[]'] = valid_msg;
                        }else{
                            APP.Forms.defaultOpts.frmValidation[v.db] = valid_;
                            APP.Forms.defaultOpts.frmValidationMsg[v.db] = valid_msg;
                        }
                        
                    }

                    if(v.type != 'hidden' && v.type != 'na' ){
                        if(v.type == 'select'){
                           toSelect2[v.db] = v.ref;
                           
                        }
                        
                        if(!frmValues.data){
                            msg+="<tr><td>"+input(v)+"</td></tr>";
                        }	
                        else
                            msg+="<tr><td>"+input(v,frmValues.data[v.db])+"</td></tr>";
                        
                        if(v.disabled == true){
                            toDisable.push(v.db);
                        }
                    }
                    else
                    msg+=input(v);
    
                });
            }else{
                msg+=frmhtml;
            }
           

            msg+="</table>";

            return msg;

        },

        /**
         * @function CreateForm
         * - Generate form base on json fields
         * @params
        */
        
        createForm: function(frmMethod="", frmAction=""){
            //bootbox.dialog()
            var msg = "";
            var title = "";
            var frmFields = {};
            var actionBtn = {};
            var toSelect2={};
            var toDisable = [];
           
                switch(frmMethod){
                    case "add": {
                        if( !frmAction ){

							frmAction = '/addData';
                        }

						frmAction = (APP.Forms.defaultOpts.frmAddAction != false ? APP.Forms.defaultOpts.frmAddAction : frmAction);


                        if(APP.Forms.defaultOpts.frmTitle == ""){
                            title = "ADD";
                        }else{
                            title = APP.Forms.defaultOpts.frmTitle;
                        }
                        
                        frmhtml = APP.Forms.defaultOpts.frmAddHtml;
                        frmFields = APP.Forms.defaultOpts.frmAddFields;
                        frmValues = APP.Forms.defaultOpts.frmAddValue;
                        actionBtn = {
                                Submit: {
                                    label: "<i class='fas fa-save'></i> &nbsp;Save",
                                    className: 'btn-outline-success',
                                    callback: function(){
                                        
                                        var validator = $( "#autoFrm" ).validate({
                                            errorElement: 'span',
                                            /*errorPlacement: function (error, element) {
                                             // error.addClass('invalid-feedback');
                                             // element.closest('.form-group').append(error);
                                            },*/
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
                                            //formData.append('',vdata);
                                           $.ajax({
                                                    type: "POST",
                                                    url: frmAction,
                                                    data: formData,
                                                    //use contentType, processData for sure.
                                                    contentType: false,
                                                    processData: false,
                                                    beforeSend: function() {
                                                        
                                                    },
                                                    success: function(msg) {
                                                        var mymsg = $.parseJSON(msg);
                                                        /* 
                                                        Success: empty / 100
                                                        Error : 200
                                                         */
                                                        
                                                        if(mymsg.msgno != undefined){
 
                                                             Swal.fire(
                                                                 mymsg.msg,
                                                                 '',
                                                                 (mymsg.msgno == 100 ? 'success' : 'warning' )
                                                             ).then((result)=>{
 
                                                                 if (typeof after_addfn === 'function') {
                                                                     after_addfn(mymsg);
                                                                 }
 
                                                             });
 
                                                        }else{
                                                             Swal.fire(
                                                                 'Successfully added',
                                                                 '',
                                                                 'success'
                                                             ).then((result)=>{
 
                                                                 if (typeof after_addfn === 'function') {
                                                                     after_addfn(mymsg);
                                                                 }
 
                                                             });
                                                        }
 
                                                        
 
                                                         APP.Datatable.reDraw(APP.Forms.defaultOpts.frm);
                                                         
 
                                                         //dt_tbl_user.ajax.reload(null, false);
                                                         //dt_tbl_user._fnReDraw;
                                                         //APP.Datatable.
                                                    },
                                                    error: function(msg) {
                                                        /*Toast.fire({
                                                            type: 'error',
                                                            title: 'oppps',
                                                            icon: 'error',
                                                        })*/
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

                        };

                        break;
                    }

                    case "edit": {
                        if( !frmAction ){

                            if(!APP.Forms.defaultOpts.frmEditAction)
                               frmAction = '/updateData';
                            else
                                frmAction = APP.Forms.defaultOpts.frmEditAction;

                        }
                        if(APP.Forms.defaultOpts.frmTitle == ""){
                            title = "EDIT";
                        }
                        else{
                            title = APP.Forms.defaultOpts.frmTitle;
                        }
                       
                        frmhtml = APP.Forms.defaultOpts.frmEditHtml;
                        frmFields = APP.Forms.defaultOpts.frmEditFields;
                        frmValues = APP.Forms.defaultOpts.frmEditValue;

                        
                        if( APP.Forms.defaultOpts.frmButton ){
                            actionBtn = APP.Forms.defaultOpts.frmButton;
                            
                        }else{
                            actionBtn = {
                                Submit: {
                                    label: "<i class='fas fa-save'></i> &nbsp;Save",
                                    className: 'btn-outline-success',
                                    callback: function(){
                                        
                                        var validator = $( "#autoFrm" ).validate({
                                            errorElement: 'span',
                                            /*errorPlacement: function (error, element) {
                                            // error.addClass('invalid-feedback');
                                            // element.closest('.form-group').append(error);
                                            },*/
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
                                            formData.append(frmValues.id.key,frmValues.id.val);
                                        $.ajax({
                                                    type: "POST",
                                                    url: frmAction,
                                                    data: formData,
                                                    //use contentType, processData for sure.
                                                    contentType: false,
                                                    processData: false,
                                                    dataType: "json",
                                                    beforeSend: function() {
                                                        
                                                    },
                                                    success: function(msg) {

                                                        Swal.fire(
                                                            'Successfully Updated',
                                                            '',
                                                            'success'
                                                        );
                                                        APP.Datatable.reDraw(APP.Forms.defaultOpts.frm);
                                                        //dt_tbl_user.ajax.reload(null, false);
                                                        //dt_tbl_user._fnReDraw;
                                                        //APP.Datatable.
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

                        };

                        }

                        

                        break;
                    }

                    case "copy": {
                        if( !frmAction ){
                            if(!APP.Forms.defaultOpts.frmCopyAction)
                               frmAction = '/copyData';
                            else
                                frmAction = APP.Forms.defaultOpts.frmCopyAction;

                        }

                        if(APP.Forms.defaultOpts.frmTitle == ""){
                            title = "COPY";
                        }else{
                            title = APP.Forms.defaultOpts.frmTitle;
                        }
                        frmhtml = APP.Forms.defaultOpts.frmCopyHtml;
                        frmFields = APP.Forms.defaultOpts.frmCopyFields;
                        frmValues = APP.Forms.defaultOpts.frmCopyValue;
                        break;
                    }

					case 'view':{

						if(APP.Forms.defaultOpts.frmTitle == ""){
                            title = "VIEW";
                        }
                        else{
                            title = APP.Forms.defaultOpts.frmTitle;
                        }

                        frmAction='';
                        frmhtml = APP.Forms.defaultOpts.frmViewHtml;
                        frmFields = APP.Forms.defaultOpts.frmViewFields;
                        frmValues = APP.Forms.defaultOpts.frmViewValue;

                        if( APP.Forms.defaultOpts.frmViewAction ){
                            actionBtn = APP.Forms.defaultOpts.frmViewAction;
                        }

                        break;
                    }
					

                    default:{

						if(APP.Forms.defaultOpts.frmTitle == ""){
                            title = "";
                        }
                        else{
                            title = APP.Forms.defaultOpts.frmTitle;
                        }

                        frmAction='';
                        frmhtml = APP.Forms.defaultOpts.frmhtml;
                        frmValues = APP.Forms.defaultOpts.frmViewValue;
                        frmFields = APP.Forms.defaultOpts.frmViewFields;
                        break;
                    }

                }
            
            frmUseoldjs = APP.Forms.defaultOpts.frmUseoldjs ;
            msg ="<form id='autoFrm' style='white-space: nowrap'><table class='table table-bordered table-sm'>";
            
            if(frmhtml == null  ){
                $.each(frmFields,function(k,v){
                    var valid_ = {};
                    var valid_msg = {};
                    
                    //is Required
                    if(v.req == true || v.req == 1){
                        valid_.required = true;
                        valid_msg.required = "This field is required";
                    }

                    //is number
                    if(v.type == "number"){
                        valid_.number = true;
                        valid_msg.number = "This field is number";
                    }

                    //max length
                    if(v.maxsize != '' && v.maxsize != undefined && v.maxsize != 0){
                        valid_.maxlength = v.maxsize;
                        valid_msg.maxlength = "The maxsize of this field is {0}";
                    }

                    if(Object.keys(valid_).length > 0 ){
                        if( v.type == 'checked'  ){
                            APP.Forms.defaultOpts.frmValidation[v.db + '[]'] = valid_;
                            APP.Forms.defaultOpts.frmValidationMsg[v.db + '[]'] = valid_msg;
                        }else{
                            APP.Forms.defaultOpts.frmValidation[v.db] = valid_;
                            APP.Forms.defaultOpts.frmValidationMsg[v.db] = valid_msg;
                        }
                        
                    }

                    if(v.type != 'hidden' && v.type != 'na' ){
                        if(v.type == 'select'){
                           toSelect2[v.db] = v.ref;
                           
                        }
                        if(v.type == 'select-multiple'){
                            toSelect2[v.db] = v.ref;
                            
                         }
                        
                        if(!frmValues.data){
                            msg+="<tr><td>"+input(v)+"</td></tr>";
                        }	
                        else
                            msg+="<tr><td>"+input(v,frmValues.data[v.db])+"</td></tr>";
                        
                        if(v.disabled == true){
                            toDisable.push(v.db);
                        }
                    }
                    else
                    msg+=input(v);
    
                });
            }else{
                msg+=frmhtml;
            }
           

            msg+="</table>";
            
            var bdiag = bootbox.dialog({
                title: title,
                message: msg,
                buttons: actionBtn,
                size:APP.Forms.defaultOpts.frmsize
            });

            bdiag.on('shown.bs.modal', function(e){
                    //dialog.removeAttr("tabindex");
                    $(".select2").select2({
                        theme: 'bootstrap4',
                        dropdownParent:$(".select2").parent(),
                        placeholder: {
                            id: '', // the value of the option
                            text: 'Please Select'
                        },
                    });

                    $.each(toSelect2,function(k,v){
                        $('#'+k).select2({
                            theme: 'bootstrap4',
                            dropdownParent:$('#'+k).parent(),
                            data:v,
                            disabled : (toDisable.indexOf(k) > -1 ? true : false),
                            placeholder: {
                                id: '', // the value of the option
                                text: 'Please Select'
                            },
                            allowClear: true
                            
                        });
                        
                        if(frmValues.data){

                            if( isJSON(frmValues.data[k]) ){
                                $('#'+k).val( $.parseJSON(frmValues.data[k]) );
                               
                            }else{
                                $('#'+k).val(frmValues.data[k]);
                            }

                            $('#'+k).trigger('change.select2');
                        }

                    });
                    
                    $.each(APP.Forms.defaultOpts.frmCustomSelect2,function(k,v){
                        
                        $(v.selector).select2({
                            theme: 'bootstrap4',
                            dropdownParent:$(v.selector).parent(),
                            data:v.data,
                            disabled : (v.disabled != undefined ? v.disabled : false),
                            placeholder: {
                                id: '', // the value of the option
                                text: 'Please Select'
                            },
                            allowClear: true
                            
                        });

                        if(v.frmvalue){
                            $(v.selector).val(v.frmvalue);
                            $(v.selector).trigger('change.select2');
                        }
                    });
                    
                    APP.Forms.defaultOpts.frmFn(frmFields , frmValues);

                    if(frmValues.pop_updata != undefined ){
                        
                        if((frmValues.pop_updata).length > 0)
                            $.each(frmValues.pop_updata, function(xk,xv){
                                
                                $("#pop_"+xk).val(xv);
                            });

                    }

                    $(".btn-popup").each(function(k,v){
                        var melem = $(v);


                        $('#'+melem.data('filter')).change(function(){
                            $("#pop_"+melem.data('id')).val('');
                            $("#"+melem.data('id')).val('');
                        });

                    });


                    $(".select2-ajax").each(function(k,v){
                        var myselect = $(this);
                        var filterid = myselect.data('filter');
                        $("#"+filterid).change(function(){
                            $.post(myurl+'/select2filter',{filterid:filterid ,filtervalue: $(this).val()},function(res){
                                myselect.select2().empty();
                                myselect.select2({
                                    theme: 'bootstrap4',
                                    dropdownParent:myselect.parent(),
                                    data:res.msgdata,
                                    disabled : (toDisable.indexOf(k) > -1 ? true : false),
                                    placeholder: {
                                        id: '', // the value of the option
                                        text: 'Please Select'
                                    },
                                    allowClear: true
                                });

                            },'json');
                            
                        });

                    });
                    
            });
            
            
        }
            
    };

    $(document).ready(function () {
        APP.Forms.init();
    });
    

})();
