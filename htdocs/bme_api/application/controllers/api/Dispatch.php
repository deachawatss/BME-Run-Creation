<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Dispatch extends RestController
{
    var $ndb;
    var $bme;
    var $has_enter_by;
    var $has_created_at;
    var $has_updated_by;
    var $has_updated_date;
    var $enableLogs = true;
    var $debug = true;
	public function __construct() {
        parent::__construct();
        $this->load->helper("barcode");
        
        $this->has_enter_by = false;
        $this->has_created_at = false;
        $this->has_updated_by = false;
        $this->has_updated_date = false;
       # $this->ndb = $this->load->database('default',true);
       # $this->bme = $this->load->database('nwfth',true);
       if($this->debug != true){
        $param = $this->input->post();
        if(isset($param['uname']) && isset($param['nwfth-session']) && $param['uname'] != '' && $param['nwfth-session'] != ''){
            #$mdb = $this->load->database('default',true);
            $msession = $this->db
                                ->where('uname',$param['uname'])
                                ->where('sessionid',$param['nwfth-session'])
                                ->get('tbl_mobile_session')
                                ->row();

            if($msession == false){
                $msg['msg'] = "Invalid Credential";
                $msg['msgno'] = -100;
                $this->response($msg, 200);
            }
            
        }else{
            $msg['msg'] = "Invalid Credential";
            $msg['msgno'] = -100;
            $this->response($msg, 200);
        }
            
       }

       $this->ndb = $this->load->database('nwfth',true);

	}

    public function finddr_post(){
        $bme = $this->load->database('nwfth',true);
        $param = $this->input->post();
		$msg = [];
      
        if($param['drno']){

           # $this->response($LotWhere, 200);
            
           
            $drdetails = $bme->select("
                                        OD.OrdNo,
                                        OH.Custname,
                                        OD.ItemKey,
                                        OD.Description,
                                        OD.QtyShip,
                                        OD.shipdate,
                                        LT.LotNo,
                                        LT.QtyIssued,
                                        CONVERT (INT, LT.IssueDocNo) AS 'DRNo',
                                        IM.Manufacturer,
                                        IM.Eccncode,
                                        OD.RowNum
                                        ")->where("LT.IssueDocNo",$bme->escape($param['drno']))
                                        ->join('OEHDR OH','OD.OrdNo = OH.Ordno')
                                        ->join('inmast IM','IM.Itemkey = OD.ItemKey')
                                        ->join('Lottransaction LT','OD.DispNo = CONVERT (INT, LT.IssueDocNo) and OD.ItemKey = LT.Itemkey and OD.RowNum = LT.IssueDocLineNo')
                                        ->get('OEDISPATCH OD')
                                        ->result();

            $msg['results'] = $drdetails;	
            $msg['drRow'] = ( $drdetails[0] ?? []);	
            $msg['msg'] = "";
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "No record available.";
            
        }
       
        $this->response($msg, 200);
    }

    public function scandr_post(){
        $bme = $this->load->database('nwfth',true);
        $db = $this->load->database('default',true);
        $param = $this->input->post();
		$msg = [];
        $drdr = [];
        if($param['drno']){
           
            $drdetails = $bme->select("
                                        OD.OrdNo,
                                        OD.ItemKey,
                                        OD.QtyShip, 
                                        CONVERT (INT, LT.IssueDocNo) AS 'DRNo',
                                        IM.Manufacturer,
                                        count(*) as mytotal,
                                        OD.RowNum
                                        ")->where("LT.IssueDocNo",$bme->escape($param['drno']))
                                        ->where("LT.TransactionType",$bme->escape("3"))
                                        ->join('OEHDR OH','OD.OrdNo = OH.Ordno')
                                        ->join('inmast IM','IM.Itemkey = OD.ItemKey')
                                        ->join('Lottransaction LT','OD.DispNo = CONVERT (INT, LT.IssueDocNo) and OD.ItemKey = LT.Itemkey and OD.RowNum = CONVERT (INT,LT.IssueDocLineNo)')
                                        ->group_by('OD.OrdNo,OD.ItemKey,OD.QtyShip,CONVERT (INT, LT.IssueDocNo),IM.Manufacturer,OD.RowNum ')
                                        ->get('OEDISPATCH OD')
                                        ->result();
            
            if( count($drdetails) ){

                foreach($drdetails as $k=>$v){

                    $where = [
                        "drno" => $v->DRNo,
                        "itemkey" => $v->ItemKey,
                        "rownum" => $v->RowNum,
                    ];

                    $mdta = $db->select("sum(qty) as qty, sum( if(statflag = 'C' ,1, 0) ) as mystat")->where($where)->get('tbl_dr_item')->row();
                    
                    if($mdta){
                        $v->qty = $mdta->qty * $v->Manufacturer;
                        $v->mystat = $mdta->mystat;
                    }
                    $drdr[] = $v;

                }

            }
            
            $msg['results'] = $drdr;	
            $msg['msg'] = "";
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "No record available.";
            
        }
       
        $this->response($msg, 200);
    }

    public function scanitemhdrlist_post(){
        $bme = $this->load->database('nwfth',true);
        $db = $this->load->database('default',true);
        $param = $this->input->post();
		$msg = [];
        $drdr = [];
      
        #if($param['lotno'] && $param['itemkey'] && $param['drno'] && $param['binno'] && $param['qty']){
        if($param['itemkey'] && $param['drno']){
           
            /*
            $drdetails = $bme->select('
                                        OD.OrdNo,
                                        OD.ItemKey,
                                        sum(OD.QtyShip) QtyShip,
                                        OD."Description"
                                        ')
                                        ->where("LT.IssueDocNo",$bme->escape($param['drno']))
                                        ->where("OD.ItemKey",$bme->escape($param['itemkey']))
                                        ->join('Lottransaction LT','OD.DispNo = CONVERT (INT, LT.IssueDocNo)')
                                        ->group_by('OD.OrdNo,OD.ItemKey,OD."Description"')
                                        ->get('OEDISPATCH OD')
                                        ->row();
            
            $drlist = $ndb
                                ->where('itemkey' , $param['itemkey'])
                                ->where('drno' , $param['drno'] )
                              #  ->where('lotno' , $param['lotno'])
                              #  ->where('binno' , $param['binno'])
                              #  ->where('qty' , $param['qty'])
                                ->get('tbl_dr_item')
                                ->result();
            */

            $drlist = $bme->select("
                                OD.OrdNo,
                                Oh.Custname,
                                OD.ItemKey,
                                OD.Description,
                                OD.QtyShip,
                                OD.shipdate,
                                LT.LotNo,
                                LT.QtyIssued,
                                CONVERT (INT, LT.IssueDocNo) AS 'DRNo',
                                IM.Manufacturer,
                                IM.Eccncode,
                                LT.BinNo,
                                OD.RowNum
                            ")
                        ->join('OEHDR OH','OD.OrdNo = OH.Ordno')
                        ->join('inmast IM','IM.Itemkey = OD.ItemKey')
                        ->join('Lottransaction LT','OD.DispNo = CONVERT (INT, LT.IssueDocNo) and OD.ItemKey = LT.Itemkey and OD.RowNum = LT.IssueDocLineNo')
                        ->where("LT.IssueDocNo",$bme->escape($param['drno']))
                        ->where("OD.ItemKey",$bme->escape($param['itemkey']))
                        ->where("OD.RowNum",$bme->escape($param['rownum']))
                        ->where("LT.TransactionType",$bme->escape("3"))
                        ->get('OEDISPATCH OD')
                        ->result();

            if( count($drlist) ){

                foreach($drlist as $k=>$v){

                    $where = [
                        "drno" => $v->DRNo,
                        "itemkey" => $v->ItemKey,
                        "lotno" => $v->LotNo,
                        "binno" => $v->BinNo,
                        "rownum" => $v->RowNum
                    ];

                    $mdta = $db->select("sum(qty) as qty,statflag")->where($where)->get('tbl_dr_item')->row();
                    
                    if($mdta){
                        $v->qty = $mdta->qty ;
                        $v->wt = $mdta->qty * $v->Manufacturer;
                        $v->statflag = $mdta->statflag ;
                    }
                    $drdr[] = $v;

                }

            }
            #$msg['drdetails'] = $drdetails;	
            $msg['drlist'] = $drdr;	
            $msg['drRow'] = ( $drlist[0] ?? []);	
            $msg['msg'] = "";
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "No record available.";
            
        }
       
        $this->response($msg, 200);
    }

    public function savedispatch_post(){
        $this->ndb = $this->load->database('default',true);
        $this->has_enter_by = true;
        $this->has_created_at = true;
        $this->has_updated_by = true;
        $this->has_updated_date = true;
        $this->load->model("Default_model_api","models");
        $param = $this->input->post();
		$msg = [
            'msg' => ''
        ];

        
        
        if($param['lotlist'] && $param['itemkey'] && $param['drno']){

           $lotlist = json_decode($param['lotlist'],1);
           $toadd = [];
           $toupdate = [];
           if( count($lotlist) ){
                $this->ndb->trans_start();
                foreach($lotlist as $k => $v){
                   $dd =  $this->ndb
                                    ->where("drno",$param['drno'])
                                    ->where("itemkey",$param['itemkey'])
                                    ->where("lotno",$k)
                                    ->where("RowNum",$v['RowNum'])
                                    ->where("binno",$v['BinNo'])
                                    ->get('tbl_dr_item')->row();
                    if(!$dd){
                        $toadd = [
                            "drno" => $param['drno'],
                            "itemkey" => $param['itemkey'],
                            "lotno" => $k,
                            "qty" => $v['qty'],
                            "statflag" => "S",
                            "binno" => $v['BinNo'],
                            "sys_enter_by" => $param['sys_enter_by'],
                            "RowNum" => $v["RowNum"]
                        ];

                        $this->models->Add('tbl_dr_item',$toadd);
                    }else{
                        $toupdate = [
                            "qty" => $v['qty'],
                            "sys_enter_by" => $param['sys_enter_by']
                        ];
                        $where = [
                            "drno" => $param['drno'],
                            "itemkey" => $param['itemkey'],
                            "lotno" => $k,
                            "binno" => $v['BinNo'],
                            
                        ];
                        $this->models->Update('tbl_dr_item',$toupdate,$where);
                    }
                }
                $this->ndb->trans_complete();

                $msg['msg'] = "Successfully Saved";
           }else{
            $msg['msg'] = "Invalid Input";
            }

        }

        $this->response($msg, 200);
    }

    public function confirmdispatch_post(){
        $this->ndb = $this->load->database('default',true);
        $this->has_enter_by = true;
        $this->has_created_at = true;
        $this->has_updated_by = true;
        $this->has_updated_date = true;
        $this->load->model("Default_model_api","models");
        $param = $this->input->post();
		$msg = [
            'msg' => ''
        ];

        
        
        if($param['lotlist'] && $param['itemkey'] && $param['drno']){

           $lotlist = json_decode($param['lotlist'],1);
           $toadd = [];
           $toupdate = [];
           if( count($lotlist) ){
                $this->ndb->trans_start();
                foreach($lotlist as $k => $v){
                   $dd =  $this->ndb
                                    ->where("drno",$param['drno'])
                                    ->where("itemkey",$param['itemkey'])
                                    ->where("lotno",$k)
                                    ->where("binno",$v['BinNo'])
                                    ->where("RowNum",$v['RowNum'])
                                    ->get('tbl_dr_item')->row();
                    if(!$dd){
                        $toadd = [
                            "drno" => $param['drno'],
                            "itemkey" => $param['itemkey'],
                            "lotno" => $k,
                            "qty" => $v['qty'],
                            "statflag" => "C",
                            "binno" => $v['BinNo'],
                            "sys_enter_by" => $param['sys_enter_by'],
                            "RowNum" => $v['RowNum']
                        ];

                        $this->models->Add('tbl_dr_item',$toadd);
                    }else{
                        $toupdate = [
                            "qty" => $v['qty'],
                            "sys_enter_by" => $param['sys_enter_by'],
                            "statflag" => "C",
                        ];
                        $where = [
                            "drno" => $param['drno'],
                            "itemkey" => $param['itemkey'],
                            "lotno" => $k,
                            "binno" => $v['BinNo'],
                            
                        ];
                        $this->models->Update('tbl_dr_item',$toupdate,$where);
                    }
                }
                $this->ndb->trans_complete();

                $msg['msg'] = "Successfully Saved";
           }else{
                $msg['msg'] = "Invalid Input";
           }

        }

        $this->response($msg, 200);
    }

    public function itemvalidate_post(){
        $bme = $this->load->database('nwfth',true);
        $ndb = $this->load->database('default',true);
        $param = $this->input->post();
		$msg = [];
      
        #if($param['lotno'] && $param['itemkey'] && $param['drno'] && $param['binno'] && $param['qty']){
        if($param['itemkey'] && $param['drno'] && $param['prodcode'] && $param['qty'] ){
           
            $drlist = $bme->select("
                                OD.OrdNo,
                                Oh.Custname,
                                OD.ItemKey,
                                OD.Description,
                                OD.QtyShip,
                                OD.shipdate,
                                LT.LotNo,
                                LT.QtyIssued,
                                CONVERT (INT, LT.IssueDocNo) AS 'DRNo',
                                IM.Manufacturer,
                                IM.Eccncode,
                                LT.BinNo
                            ")
                        ->join('OEHDR OH','OD.OrdNo = OH.Ordno')
                        ->join('inmast IM','IM.Itemkey = OD.ItemKey')
                        ->join('Lottransaction LT','OD.DispNo = CONVERT (INT, LT.IssueDocNo) and OD.ItemKey = LT.Itemkey')
                        ->where("LT.IssueDocNo",$bme->escape($param['drno']))
                        ->where("OD.ItemKey",$bme->escape($param['itemkey']))
                        ->get('OEDISPATCH OD')
                        ->result();

            #$msg['drdetails'] = $drdetails;	
            $msg['drlist'] = $drlist;	
            $msg['drRow'] = ( $drlist[0] ?? []);	
            $msg['msg'] = "";
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "No record available.";
            
        }
       
        $this->response($msg, 200);
    }

    public function saveSalesDr_post(){
        $bme = $this->load->database('nwfth',true);
        $ndb = $this->load->database('default',true);
        $this->load->model("Default_model_api","models");
        $param = $this->input->post();
        $msg = [];

        $toupdate = [
            "statflag" => "F",
            "updated_by" => $param["sys_enter_by"]
        ];
        $where = [
            "drno" => $param['drno'],
        ];

        $ndb->update('tbl_dr_item',$toupdate,$where);

        if($param['drno']){
            $where = [
            "DispNo" => $this->ndb->escape($param['drno'])
            ];

            $this->models->Update("OEDISPATCH",$toupdate,$where);
            $msg['msg'] = "Successfully Saved";
        }

        
        $this->response($msg, 200);
    }

    public function testprinter_post(){
    /*
      $this->load->library('Print_send_lpr');
      $text = "Hello world!";
      $lpr = new Print_send_lpr();
        $lpr->set_port("9100");
        $lpr->set_host("192.168.1.90");
        $lpr->set_data($text);
        $lpr->print_job("Queue 1");
        $lpr->get_debug();
    */
        /*
        $ip_address = "192.168.1.90";
        $port = 9100;
        
        // Set the name of the ZPL template saved on the printer
        $template_name = "AUTOPRINT";
        
        // Set the variable data for the template (if any)
        $variable_data = "^FN1^FDMy Variable Data^FS";
        
        // Send the recall command and variable data as the POST request body
        $post_data = "^XA^LH0,0^XFR:".$template_name+"^FS".$variable_data."^XZ";
        
        // Create the POST request
        $url = "http://".$ip_address.":".$port;
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_data);
        
        // Send the request
        $response = curl_exec($request);
        
        // Check for errors
        if(curl_errno($request)){
            echo 'Curl error: ' . curl_error($request);
        }
        
        // Close the request
        curl_close($request);
        */

        $ip_address = "192.168.1.90";
        $port = 9100;

        // Set the ZPL code to be printed
        $zpl_code = "^XA^FO50,50^A0N,50,50^FDHello, World!^FS^XZ";

        // Create the POST request
        $url = "http://".$ip_address.":".$port;
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, $zpl_code);

        // Send the request
        $response = curl_exec($request);

        // Check for errors
        if(curl_errno($request)){
            echo 'Curl error: ' . curl_error($request);
        }

        // Close the request
        curl_close($request);
        $this->response([], 200);
    }
}
