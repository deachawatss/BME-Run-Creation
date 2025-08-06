
function input(param,val = undefined){
    //console.log(param);
    if(val == undefined) {
        
		if(param.val==undefined ){
            val="";
		}
		
    }

    if(param.readonly==undefined){
        param.readonly = 0;
    }

    var ro = (param.readonly == 1 ? " readonly='readonly' " : "");
    
    
    var on_click="";
    var on_change="";
    var btn_textClick="";

    if(param.btn_textClick!=undefined && param.btn_textClick!=''){
        btn_textClick="onclick='"+param.btn_textClick+"'";
    }

    if(param.onclick!=undefined && param.onclick!=''){
        on_click="onclick='"+param.onclick+"()'";
    }
    
    if(param.onchange!=undefined && param.onchange!=''){
        on_change="onchange='"+param.onchange+"()'";
    }
    
    others = "";
    if(param.others!=undefined && param.others!=''){
        others = param.others;
    }

	if(param.req==0)
		param.req=undefined;
	
	//console.log(val);


    switch(param.type){

        case 'hidden':{
          var mval=(val!="" ? val : (param.val==null ? "" :param.val) );
            return "<input  type='hidden' value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
        break;
        }
        
        case 'na':{
          var mval=(val!="" ? val : (param.val==null ? "" :param.val) );
            return "";
        break;
        }

        case 'text':{
           var mval=(val!="" ? val :param.val);
            //console.log(mval);
            return "<label for='"+param.db+"'>"+param.th + (param.req!=undefined? "<span style='color:red'>*</span>" : "") +"</label></td><td><input "+on_change+" "+on_click+" "+ ro +" placeholder='"+param.th+"' "+ (param.req!=undefined? "required" : "") +"   type='text' class='form-control' value='"+textval(mval)+"' name='"+param.db+"' id='"+param.db+"' />";
        break;
        }

        case 'btn-text':{
            var mval=(val!="" ? val :param.val);
           
             return "<label for='"+param.db+"'>"+param.th + (param.req!=undefined? "<span style='color:red'>*</span>" : "") +"</label></td><td>" +
                    "<div class='input-group '>"+
                    "<input "+on_change+" "+on_click+" "+ ro +" placeholder='"+param.th+"' "+ (param.req!=undefined? "required" : "") +"   type='text' class='form-control' value='"+textval(mval)+"' name='"+param.db+"' id='"+param.db+"' />"+
                    '<button class="btn btn-outline-secondary" type="button" id="btn-text-'+param.db+'" data-id="'+param.db+'" '+btn_textClick+'>Search</button>'+
                    "</div>";
         break;
         }

		case 'number':{
			var mval=(val!="" ? val :param.val);
		   
			 return "<label for='"+param.db+"'>"+param.th + (param.req!=undefined? "<span style='color:red'>*</span>" : "") +"</label></td><td><input "+on_change+" "+on_click+" "+ ro +" placeholder='"+param.th+"' "+ (param.req!=undefined? "required" : "") +"   type='number' class='form-control' value='"+textval(mval)+"' name='"+param.db+"' id='"+param.db+"' />";
		 break;
		 }
        
        case 'date':{
           var mval=(val!="" ? val :param.val);
            return "<label for='"+param.db+"'>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td><input "+on_change+" "+on_click+" "+ ro +" placeholder='"+param.th+"' "+ (param.req!=undefined? "required" : "") +" type='date' class='form-control' value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
        break;
        }
        
        case 'datetime':{
           var mval=(val!="" ? val :param.val);
           
           if(mval!=""){
                mval= new Date(mval);
                //console.log(mval.ctoISOString());
                mval=mval.ctoISOString();
            }
            
            return "<label for='"+param.db+"'>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td><input "+on_change+" "+on_click+" "+ ro +" placeholder='"+param.th+"'  type='datetime-local' class='form-control' "+ (param.req!=undefined? "required" : "") +" value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
        break;
        }


        case 'textarea':{
            var mval=(val!="" ? val :param.val);
             return "<label for='"+param.db+"'>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td><textarea "+on_change+" "+on_click+" "+ ro +" placeholder='"+param.th+"' class='form-control'  "+ (param.req!=undefined? "required" : "") +" name='"+param.db+"' id='"+param.db+"' >"+textval(mval)+"</textarea>";
         break;
         }
		
		/*
        case 'select':{
            var mval=(val!="" ? val :param.val);
		return "<label for='"+param.db+"'>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td><select "+on_change+" "+on_click+" class='form-control select2"+ (param.req!=undefined? "form_select_req" : "") +"' name='"+param.db+"' id='"+param.db+"'>"+genSelect(param.ref,mval)+"</select>";
        break;
        }
		*/
		case 'select':{
            var mval=(val!="" ? val :param.val);
		return "<label for='"+param.db+"'>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td><select  "+on_change+" "+on_click+"  class='form-control select2' "+ (param.req!=undefined? "required" : "") +" name='"+param.db+"' id='"+param.db+"' "+others+"><option></option></select>";
        break;
        }

        case 'select-ajax':{
            var mval=(val!="" ? val :param.val);
		return "<label for='"+param.db+"'>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td><select data-filter='"+ ( param.filter != undefined  ? param.filter : ""  ) +"' "+on_change+" "+on_click+"  class='form-control select2 select2-ajax' "+ (param.req!=undefined? "required" : "") +" name='"+param.db+"' id='"+param.db+"' "+others+"><option></option></select>";
        break;
        }

        case 'select-multiple':{
            var mval=(val!="" ? val :param.val);
		return "<label for='"+param.db+"'>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td><select  multiple='multiple' "+on_change+" "+on_click+"  class='form-control select2' "+ (param.req!=undefined? "required" : "") +" name='"+param.db+"[]' id='"+param.db+"' "+others+"><option></option></select>";
        break;
        }

        case 'checked':{
            var mval=(val!="" ? val :param.val);
            
            return "<label>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td>"+genCheck(param,mval)+"";
        break;
        }

        case 'radio':{
            //console.log(val);
            var mval=(val!="" ? val :param.val);
             return "<label>"+param.th+ (param.req!=undefined? "<span style='color:red'>*</span>" : "")+"</label></td><td>"+genRadio(param,mval)+"";
        break;
        }
		
        case 'pop_up':{
            var mval=val;
            return '<p style="font-weight:bold">'+param.th+(param.req==1? "<span style='color:red;'>*</span>" : "") +'</p></td><td><div class="input-group mb-3">'+
                     '<input type="text" '+ (param.req!=undefined? "required" : "") +'  class="form-control '+(param.req==1? "form_text_req" : "")+'" id="pop_'+param.db+'" name="pop_'+param.db+'" placeholder="'+param.th+'" aria-label="'+param.th+'" readonly aria-describedby="button-addon2">'+
                     '<input value="'+mval+'" '+ (param.req!=undefined? "required" : "") +' type="hidden" class="form-control '+(param.req==1? "form_text_req" : "")+'" id="'+param.db+'" name="'+param.db+'" placeholder="'+param.th+'" aria-label="'+param.th+'" readonly aria-describedby="button-addon2">'+
                     '<div class="input-group-append">'+
                       '<a class="btn btn-outline-secondary btn-popup" data-callback_pop="'+(param.pop_up_callback)+'" id="pop-up'+(param.db)+'" data-row="'+(param.row)+'" data-id="'+param.db+'" data-filter="'+ ( param.filter != undefined  ? param.filter : ""  ) +'" >Search</a>'+
                     '</div>'+
                   '</div>';
            break;
        }

		default:{
			var mval=(val!="" ? val : (param.val!="" && param.val!= undefined ? param.val: "" ));
            return "<label for='"+param.db+"'>"+param.th + (param.req!=undefined? "<span style='color:red'>*</span>" : "") +"</label></td><td><input "+on_change+" "+on_click+" "+ro+" placeholder='"+param.th+"' "+ (param.req!=undefined? "required" : "") +"  type='text' class='form-control a' value=\""+textval(mval)+"\" name='"+param.db+"' id='"+param.db+"' />";
			break;
		}

    }

}

//format date
if (!Date.prototype.ctoISOString) {
  (function() {

    function pad(number) {
      if (number < 10) {
        return '0' + number;
      }
      return number;
    }

    Date.prototype.ctoISOString = function() {
      return this.getFullYear() +
        '-' + pad(this.getMonth() + 1) +
        '-' + pad(this.getDate()) +
        'T' + pad(this.getHours()) +
        ':' + pad(this.getMinutes());
    };

  })();
}

function genSelect(json,selected){
    var shtml="<option value=''>Please Select</option>";
        
        $.each(json,function(k,v){
            shtml+="<option value='"+v.value+"' "+(v.value==selected ? "selected='selected'":"")+">"+v.label+"</option>";
        });

     return shtml;
 }

 function genCheck(json,selected){
    var shtml = "<ul class=\"checkbox-grid\">";
    
    var myval = [];
    if(textval(selected) != '')
        myval =  JSON.parse(selected);
        $.each(json.ref,function(k,v){
            var xx = ($.inArray(v.value, myval) > -1 ? "checked='checked'" : "");
            shtml+="<li><div class='form-check'><input "+xx+" type='checkbox' class='form-check-input' name='"+json.db+"[]' value='"+v.value+"' id='"+json.db+"_"+k+"'><label class='form-check-label' for='"+json.db+"_"+k+"'>"+v.label+"</label></div></li>";
        });
        shtml += "</ul>";
     return shtml;
 }
 
 function genRadio(json,selected){
    var shtml = "<ul class=\"checkbox-grid\">";
        
        $.each(json.ref,function(k,v){
            shtml+="<li><div class='form-check'><input "+(v.value==selected ? "checked='checked'":"" )+" type='Radio' clas='form-check-input' name='"+json.db+"' value='"+v.value+"' id='"+json.db+"_"+k+"'> <label class='form-check-label' for='"+json.db+"_"+k+"'> "+v.label+"</label></div></li>";
        });
        shtml += "</ul>";
     return shtml;
 }
 
function w3input(param,val,req=0){
    
    if(val == undefined) {
        val="";

        
    }

    switch(param.type){
        
        case 'na':{
          var mval=(val!="" ? val : (param.val==null ? "" :param.val) );
            return "";
        break;
        }
        
        case 'hidden':{
          var mval=(val!="" ? val : (param.val==null ? "" :param.val) );
            return "<input  type='hidden' value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
        break;
        }

        case 'text':{
           var mval=(val!="" ? val :param.val);
            //return "<label for='"+param.db+"'>"+param.th+"</label><input placeholder='"+param.th+"'  type='text' class='form-control' value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
            $x='<input style="background-color:#fdfde1" class="w3-input '+ (req==1? "form_text_req" : "") +'" value="'+mval+'" name="'+param.db+'" id="'+param.db+'" type="text"><label class="text-center" style="width:100%;">'+param.th+ (req==1? "<span style='color:red;'>*</span>" : "") +'</label>';
            return $x;
        break;
        }
        
        case 'ctext':{
           var mval=(val!="" ? val :param.val);
            //return "<label for='"+param.db+"'>"+param.th+"</label><input placeholder='"+param.th+"'  type='text' class='form-control' value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
            $x=' <input class=" '+ (req==1? "form_text_req" : "") +'" value="'+mval+'" name="'+param.db+'" id="'+param.db+'" type="text">';
            return $x;
        break;
        }
        
        
        case 'date':{
           var mval=(val!="" ? val :param.val);
            //return "<label for='"+param.db+"'>"+param.th+"</label><input placeholder='"+param.th+"'  type='date' class='form-control' value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
             $x='<input style="background-color:#fdfde1"  class="w3-input '+ (req==1? "form_text_req" : "") +'" value="'+mval+'" name="'+param.db+'" id="'+param.db+'" type="date"><label class="text-center" style="width:100%;">'+param.th+(req==1? "<span style='color:red;'>*</span>" : "") +'</label>';
            return $x;
        break;
        }
        
        case 'datetime':{
            
           var mval=(val!="" ? val :param.val);
            
            if(mval!=""){
                mval= new Date(mval);
                mval=mval.toISOString();
            }
            
            //return "<label for='"+param.db+"'>"+param.th+"</label><input placeholder='"+param.th+"'  type='date' class='form-control' value='"+mval+"' name='"+param.db+"' id='"+param.db+"' />";
             $x='<input style="background-color:#fdfde1"  class="w3-input '+ (req==1? "form_text_req" : "") +'" value="'+mval+'" name="'+param.db+'" id="'+param.db+'" type="datetime-local"><label class="text-center" style="width:100%;">'+param.th+(req==1? "<span style='color:red;'>*</span>" : "") +'</label>';
            return $x;
        break;
        }

        case 'textarea':{
            var mval=(val!="" ? val :param.val);
             return "<label   for='"+param.db+"'>"+param.th+(req==1? "<span style='color:red;'>*</span>" : "") +"</label><textarea style=\"background-color:#fdfde1\" placeholder='"+param.th+"' class='form-control "+(req==1? "form_text_req" : "")+"' name='"+param.db+"' id='"+param.db+"' > "+mval+"</textarea>";
         break;
         }

        case 'select':{
            var mval=(val!="" ? val :param.val);
            return '<select style="background-color:#fdfde1"  class="w3-input '+ (req==1? "form_select_req" : "") +'" name="'+param.db+'" id="'+param.db+'">'+genSelect(xref[param.db],mval)+'</select><label class="text-center" style="width:100%;" for="'+param.db+'">'+param.th+ (req==1? "<span style='color:red;'>*</span>" : "") +'</label>';
           
            // return "<label for='"+param.db+"'>"+param.th+"</label><select class='form-control' name='"+param.db+"' id='"+param.db+"'>"+genSelect(xref[param.db],mval)+"</select>";
        break;
        }
        
        case 'cselect':{
            var mval=(val!="" ? val :param.val);
            return ' <select class=" '+ (req==1? "form_select_req" : "") +'" name="'+param.db+'" id="'+param.db+'">'+genSelect(xref[param.db],mval)+'</select>';
           
            // return "<label for='"+param.db+"'>"+param.th+"</label><select class='form-control' name='"+param.db+"' id='"+param.db+"'>"+genSelect(xref[param.db],mval)+"</select>";
        break;
        }
        
        case 'checked':{
            var mval=(val!="" ? val :param.val);
            
            return "<label>"+param.th+"</label>"+genCheck(xref[param.db],mval)+"</select>";
        break;
        }

        case 'radio':{
            //console.log(val);
            var mval=(val!="" ? val :param.val);
             return "<p>"+param.th+"</p>"+genRadio(xref[param.db],mval)+"</select>";
        break;
        }

    }

}

function validate(){
    
    var myreq=0;
    
    $(".form_text_req , .form_select_req").each(function(k,v){
            
            if($(v).val()==''){
                $(v).css({"background-color":"#ec8d89"});
                myreq++;
            }else{
                $(v).css({"background-color":"#fdfde1"});
            }
            
         
    });
    return myreq;
}

function textval(e){
    
    if(e!=undefined && e!=null){

        if(isNaN(e)){
            
        return e.replace("\"","'");
        }else{
            return e;
        }
    }
    return "";
    
}

function checkval(e,i){
    if(e==i){
        return "checked='checked'";
    }

    return "";
}

'use strict';

window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

(function(global) {
	var MONTHS = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	];

	var COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	var Samples = global.Samples || (global.Samples = {});
	var Color = global.Color;

	Samples.utils = {
		// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
		srand: function(seed) {
			this._seed = seed;
		},

		rand: function(min, max) {
			var seed = this._seed;
			min = min === undefined ? 0 : min;
			max = max === undefined ? 1 : max;
			this._seed = (seed * 9301 + 49297) % 233280;
			return min + (this._seed / 233280) * (max - min);
		},

		numbers: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 1;
			var from = cfg.from || [];
			var count = cfg.count || 8;
			var decimals = cfg.decimals || 8;
			var continuity = cfg.continuity || 1;
			var dfactor = Math.pow(10, decimals) || 0;
			var data = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = (from[i] || 0) + this.rand(min, max);
				if (this.rand() <= continuity) {
					data.push(Math.round(dfactor * value) / dfactor);
				} else {
					data.push(null);
				}
			}

			return data;
		},

		labels: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 100;
			var count = cfg.count || 8;
			var step = (max - min) / count;
			var decimals = cfg.decimals || 8;
			var dfactor = Math.pow(10, decimals) || 0;
			var prefix = cfg.prefix || '';
			var values = [];
			var i;

			for (i = min; i < max; i += step) {
				values.push(prefix + Math.round(dfactor * i) / dfactor);
			}

			return values;
		},

		months: function(config) {
			var cfg = config || {};
			var count = cfg.count || 12;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = MONTHS[Math.ceil(i) % 12];
				values.push(value.substring(0, section));
			}

			return values;
		},

		color: function(index) {
			return COLORS[index % COLORS.length];
		},

		transparentize: function(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}
	};

	// DEPRECATED
	window.randomScalingFactor = function() {
		return Math.round(Samples.utils.rand(-100, 100));
	};

	// INITIALIZATION

	Samples.utils.srand(Date.now());

	// Google Analytics
	/* eslint-disable */
	if (document.location.hostname.match(/^(www\.)?chartjs\.org$/)) {
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-28909194-3', 'auto');
		ga('send', 'pageview');
	}
	/* eslint-enable */

}(this));


const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
});

var xdxpop;
function popUpSelect(h,e){
	$("#"+e).val(h);
	$("#pop_"+e).val(mypopup_ref[e][h]);
	xdxpop.modal('hide');
}

function popUp(e){
	var info=in_add_tbl_srm[e]["pop-header"];
	var info_val=in_add_tbl_srm[e]["pop-value"];
	
	
	
	var hh=info.split(",");
	
	var mytbl="<div><table class='table table-sm table-hover' id='xpopUp'><thead>";
	
	$.each(hh,function(k,v){
		mytbl+="<th>"+v+"</th>";
	});
	
	mytbl+="</thead>";
	
	$.each(xref[e],function(k,v){
		mytbl+="<tr onclick='popUpSelect(\""+v[info_val]+"\",\""+e+"\")'>";
			$.each(v,function(kk,vv){
				mytbl+="<td>"+vv+"</td>";
			});
		mytbl+="</tr>";
	});
	mytbl+="</table></div>";
	
	xdxpop=bootbox.dialog({
		'message':mytbl,
		onShow: function(xxxx) {
			//alert('d')
			
			$('#xpopUp').DataTable();
			
		} 
	});
	 
	
}

function ajaxFilterCmd(a,b,c=""){
	var ajaxf=ajaxFilter[a];
	var pfield_name=a;
	var cfield_name=ajaxf.child_field_name;
	
	/*****if TSR*****/
	var mval=$(b).val();
	
	if(a=="TSR" ){
		$("#Customer").val("0-"+mval);
		$("#pop_Customer").val(mypopup_ref["Customer"]["0-"+mval]);//
	}
	//console.log(xref[ajaxf.child_field_name]);
	/***********************/
	
	

	
	

	
}


$(document).on('click','.btn-popup',function(){

    var mytbl = '<table class="table table-sm table-hover table-bordered" id="xpopUp" style="width:100%!important"></table>';
    var mevent = $(this);
    xdxpop=bootbox.dialog({
        'title' : '<span></span>',
		'message':mytbl,
        'size':'lg',
		onShow: function(xxxx) {
            
            $.post(myurl + '/LookUp',{parent_val:$("#"+mevent.data('filter')).val(),lookup:mevent.data('row')},function(res){
               var dt = $('#xpopUp').DataTable({
                    data: res,
                    columns : pop_updata[ mevent.data('id') ],
                    destroy: true,
                    processing: true,
                });

                $('#xpopUp tbody').on('click', 'tr', function () {
                    var data = dt.row(this).data();

                    $("#pop_"+mevent.data('id')).val(data.Customer_Name);
                    $("#"+mevent.data('id')).val(data.Customer_Key);
                    xdxpop.modal('hide');

                    if(mevent.data('callback_pop') != '' && mevent.data('callback_pop') != undefined ){
                        eval(mevent.data('callback_pop') + "(data)");
                    }

                });

                

            },"json");

			
			
		} 
	});

});

function getvalobj(obj,key){
    var  val = '';
    if(textval(key) != '')
        $.each(obj,function(k,v){

            if(v.id == key){
                val = v.text;
            }

        });

    return val;

}

function arrtotext(val){
    var str = '';
    console.log(val);
    if( isJson(val) ){
       str = val.join();
    }

    return str;
}

function strpad(n){

    return String(n).padStart(7,"0");

}

function isJson(item) {
    item = typeof item !== "string"
        ? JSON.stringify(item)
        : item;

    try {
        item = JSON.parse(item);
    } catch (e) {
        return false;
    }

    if (typeof item === "object" && item !== null) {
        return true;
    }

    return false;
}

function printCrossword(printContainer) {
    var WindowObject = window.open('', "PrintWindow", "width=900,height=900,top=200,left=200,toolbars=no,scrollbars=no,status=no,resizable=no");
    WindowObject.document.writeln(printContainer);
    WindowObject.focus();
    WindowObject.print();
    WindowObject.close();
}