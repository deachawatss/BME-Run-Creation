<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Fgtransfer extends RestController
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
        $this->load->helper("msg");
        
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
                $msg['msg'] = return_msg("-100");
                $msg['msgno'] = -100;
                $this->response($msg, 200);
            }
            
        }else{
            $msg['msg'] = return_msg("-100");
            $msg['msgno'] = -100;
            $this->response($msg, 200);
        }
            
       }

       $this->ndb = $this->load->database('nwfth2',true);

	}

    public function transfer_post(){
        $bme = $this->load->database('nwfth2',true);
        $param = $this->input->post();
		$msg = [];
        $this->load->model("Default_model_api","models");
       # $batchticket = $param['batchticket'];


       
       if((isset($param['batchticket']) && isset($param['BinNo']) && isset($param['item'])) && ($param['batchticket'] != '' && $param['BinNo'] != '' && $param['item'] != '') ){
        
                $bcode = getBarcodeData($param['item']);
            
                if( isset($bcode['02'])){
                    $LotWhere['LotTransaction.ItemKey'] = $bme->escape($bcode['02']);
                }else{
                    $msg['results'] = [];
                    $msg['norecord'] = 0;
                    $msg['msg'] = "Invalid input";
                    $this->response($msg, 200);
                    die();
                }

                if( !isset($bcode['17'])){
                    $msg['results'] = [];
                    $msg['norecord'] = 0;
                    $msg['msg'] = "Invalid input";
                    $this->response($msg, 200);
                    die();
                }

                if( !isset($bcode['10'])){
                    $msg['results'] = [];
                    $msg['norecord'] = 0;
                    $msg['msg'] = "Invalid input";
                    $this->response($msg, 200);
                    die();
                }
        
            
            $batchinfo = $bme
                            ->where('ReceiptDocNo',$bme->escape($param['batchticket']))
                            #->where('Processed',$bme->escape('N'))
                            ->where('TransactionType',18)
                            #->where('BinNo',$bme->escape('A-FG_STAGING'))
                            ->get('LotTransaction')->row();
            
            $BinDetails = $bme 
                                ->where("BinNo",$bme->escape($param['BinNo']))
								->get('BINMaster')
								->row();

            #Validate Bin
            if($BinDetails == false){
                $msg['msg'] = return_msg("103");;
                $msg['msgno'] = 103;
                $this->response($msg, 200);
                die();
            }

            if($param['BinNo'] == 'B-FG_STAGING' ){
                $msg['msg'] = return_msg("104");;
                $msg['msgno'] = 104;
                $this->response($msg, 200);
                die();
            }
            
            #Validate Batchticket
            if($batchinfo != false){

                if($batchinfo->BinNo != 'B-FG_STAGING'){
                    $msg['msg'] = return_msg("106");;
                    $msg['msgno'] = 106;
                    $this->response($msg, 200);
                    die();
                }
                
                if($batchinfo->Processed == 'N'){

                    #Expiry Date
                    $day   = substr($bcode['17'], 0, 2);
                    $month	 = substr($bcode['17'], 2, 2);
                    $year  = substr($bcode['17'], 4, 2);
                    $expdate = date("F d Y", mktime(0, 0, 0, $month, $day, $year));

                    #Received Date
                    $rday   = substr($bcode['10'], 0, 2);
                    $rmonth	 = substr($bcode['10'], 2, 2);
                    $ryear  = substr($bcode['10'], 4, 2);
                    $rdate = date("F d Y", mktime(0, 0, 0, $rmonth, $rday, $ryear));

                    $udata = [
                        "BinNo" => $param['BinNo'],
                        "Lotno" => $bcode['10'],
                        "DateExpiry" => $expdate,
                        "DateReceived" => $rdate
                    ];
                    
                    $uwhere = [
                        'ReceiptDocNo'=> $bme->escape($param['batchticket']),
                        'Processed'=> $bme->escape('N'),
                        'BinNo'=> $bme->escape('B-FG_STAGING'),
                        'TransactionType' => $bme->escape(18),
                    ];

                    $this->models->Update("LotTransaction",$udata,$uwhere);
                    $msg['msg'] = return_msg("201");;
                    $msg['msgno'] = 201;

                }else{
                    $msg['msg'] = return_msg("102");;
                    $msg['msgno'] = 102;
                }

            }else{
                $msg['msg'] = return_msg("105");;
                $msg['msgno'] = 105;
            }

       }else{

            $msg['msg'] = return_msg("101");;
            $msg['msgno'] = 101;

       }
       
        $this->response($msg, 200);
    }

    
   
}
