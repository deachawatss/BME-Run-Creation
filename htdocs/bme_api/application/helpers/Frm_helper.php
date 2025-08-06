<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('frminput ')){
function frminput($param)
        {       
                $inp_add=array();
                $inp_edit=array();
                $xref=array();
             
                
                foreach($param as $k=>$v){
                        
                        if(key_exists('frm',$v)){
                                $input=$v['frm']['type'];
                                    
                        }else{
                                $input="text";
                        }
                        
                        switch($input){
                                
                                case 'hidden':{
                                      #  $inp_add[$v['db']]="<input  type='hidden' value='".$v['frm']['val']."' name='".$v['db']."' id='".$v['db']."' />";
                                      #  $inp_edit[$v['db']]="<input type='hidden' value='\"+myresult.".$v['db']."+\"' name='".$v['db']."' id='".$v['db']."' />";
                                       
                                        break;
                                }
                                case 'textarea':{
                                       # $inp_add[$v['db']]="<label for='".$v['db']."'>".$v['th']."</label><textarea class='form-control'  name='".$v['db']."' id='".$v['db']."' ></textarea>";
                                       # $inp_edit[$v['db']]="<label for='".$v['db']."'>".$v['th']."</label><textarea class='form-control'  name='".$v['db']."' id='".$v['db']."' >\"+myresult.".$v['db']."+\"</textarea>";
                                      
                                        break;
                                }
                                case 'radio':{
                                        if(is_object($v['frm']['opt']))
                                        $myopt=json_decode(json_encode($v['frm']['opt']),1);
                                        else
                                        $myopt=$v['frm']['opt'];

                                        foreach( $myopt as $ku=>$vu){

                                                $xref[$v['db']][]=array(
                                                        "db"=>$v["db"],
                                                        "value"=>( is_array($vu) ? $vu['value']:$vu->value ),
                                                        "label"=>( is_array($vu) ? $vu['label']:$vu->label )
                                                );

                                        }
                                        #$inp_add[$v['db']]="<p>".$v['th']."</p>genRadio(xref.".$v["db"].",'".@$v['frm']['val']."')";
                                        #$inp_edit[$v['db']]="<p>".$v['th']."</p>genRadio(xref.".$v["db"].",'\"+myresult.".$v['db']."\"+')";
                                
                                        break;
                                }
                                case 'check':{

                                        if(is_object($v['frm']['opt']))
                                        $myopt=json_decode(json_encode($v['frm']['opt']),1);
                                        else
                                        $myopt=$v['frm']['opt'];

                                        foreach( $myopt as $ku=>$vu){
                                                $xref[$v['db']][]=array(
                                                        "db"=>$v["db"],
                                                        "value"=>( is_array($vu) ? $vu['value']:$vu->value ),
                                                        "label"=>( is_array($vu) ? $vu['label']:$vu->label )
                                                );

                                        }
                                        #$inp_add[$v['db']]="<p>".$v['th']."</p>genCheck(xref.".$v["db"].",'".@$v['frm']['val']."')";
                                        #$inp_edit[$v['db']]="<p>".$v['th']."</p>genCheck(xref.".$v["db"].",'\"+myresult.".$v['db']."\"+')";
                                
                                        break;
                                }
                                case 'select':{
                                        if(is_object($v['frm']['opt']))
                                        $myopt=json_decode(json_encode($v['frm']['opt']),1);
                                        else
                                        $myopt=$v['frm']['opt'];

                                        foreach( $myopt as $ku=>$vu){
                                                $xref[$v['db']][]=array(
                                                        "db"=>$v["db"],
                                                        "value"=>( is_array($vu) ? @$vu['value']:@$vu->value ),
                                                        "label"=>( is_array($vu) ? @$vu['label']:@$vu->label )
                                                );

                                        }
                                        #$inp_add[$v['db']]="<select class='form-control' name='".$v['db']."' id='".$v['db']."'>\"+genSelect(xref.".$v['db'].",'".@$v['frm']['val']."')+\"</select>";
                                        #$inp_edit[$v['db']]="<select class='form-control' name='".$v['db']."' id='".$v['db']."'>\"+genSelect(xref.".$v['db'].",'\"+myresult.".$v['db']."\"+')+\"</select>";
                                       
                                        break;
                                }
                                case 'popup':{
                                        #$inp_add[$v['db']]="<textarea class='form-control'  name='".$v['db']."' id='".$v['db']."' ></textarea>";
                                        #$inp_edit[$v['db']]="<textarea class='form-control'  name='".$v['db']."' id='".$v['db']."' >\"+myresult.".$v['db']."+\"</textarea>";
                                
                                        break;
                                }
                                default:{
                                        
                                        #$inp_add[$v['db']]="<label for='".$v['db']."'>".$v['th']."</label><input class='form-control' type='text' value='".@$v['frm']['val']."' name='".$v['db']."' id='".$v['db']."' />";
                                        #$inp_edit[$v['db']]="<label for='".$v['db']."'>".$v['th']."</label><input class='form-control' type='text' value='\"+myresult.".$v['db']."+\"' name='".$v['db']."' id='".$v['db']."' />";
                                break;
                                }
                        }
                        
                        
                        $inp_add[$v['db']]=$v;
                        $inp_add[$v['db']]["val"]=(@$v["val"]=="" ? "":@$v["val"]);
                        $inp_add[$v['db']]["type"]=(@$v['frm']['type']!="" ? $input:"text");
                       

                }

                return array("inp_add"=>$inp_add,"inp_edit"=>$inp_edit,"xref"=>$xref);
        }
}

if ( ! function_exists('cdate_format')){
        function cdate_format($param,$f="m/d/Y"){
            $toret="";
            if($param!="" && $param!="0000-00-00" ){
                $toret=date($f,strtotime($param));
            }
            return $toret;
        }
}