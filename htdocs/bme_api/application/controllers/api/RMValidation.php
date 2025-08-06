<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class RMValidation extends RestController
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

    public function getbatch_post(){
        $bme = $this->load->database('nwfth',true);
        $param = $this->input->post();
		$msg = [];
        $this->load->model("Default_model_api","models");

        if(isset($param['batchticket']) && $param['batchticket'] != ""){
            $myrec = $bme->select("Lott.Issuedocno, Lott.itemkey, Lott.lotno, Lott.Qtyissued, PN.formulaid, PI.Lineid")
                         ->join("PNMAST PN","PN.Batchno = Lott.Issuedocno")
                         ->join("PNITEM PI","PI.BatchNo = Lott.issuedocno and PI.itemkey = Lott.itemkey")
                         ->where("Lott.issuedocno",$bme->escape($param['batchticket']))
                         ->where("PI.LineTyp ",$bme->escape("FI"))
                         ->where("PI.nwf_stat is Null")
                         ->get("lottransaction Lott")->result();

            if( count($myrec) >0  ){
                $mdt = $myrec[0];
                $msg['formulaid'] = $mdt->formulaid;
            }else{
                $msg['formulaid'] = '';
            }
            $msg['norecord'] = count($myrec);
            $msg['results'] = $myrec;
            $msg['msg'] = '';
            $msg['msgno'] = 201;
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "Invalid input";
            
        }

        $this->response($msg, 200);
    }

    public function setitem_post(){

        $bme = $this->load->database('nwfth',true);
        $param = $this->input->post();
		$msg = [];
        $this->load->model("Default_model_api","models");


        if(isset($param['batchticket']) && $param['batchticket'] != "" && isset($param['Lineid']) && $param['Lineid'] != ""){
            /*
            $myrec = $bme
                        ->select("Lott.Issuedocno, Lott.itemkey, Lott.lotno, Lott.Qtyissued, PN.formulaid")
                        ->join("PNMAST PN","PN.Batchno = Lott.Issuedocno")
                        ->join("PNITEM PI","PI.BatchNo = Lott.issuedocno and PI.itemkey = Lott.itemkey")
                        ->where("Lott.issuedocno",$bme->escape($param['batchticket']))
                        ->where("PI.LineTyp ",$bme->escape("FI"))
                        ->where("PI.nwf_stat is Null")
                        ->get("lottransaction Lott")->result();
            */

            $bme->where("Lineid",$bme->escape($param["Lineid"]));
            $bme->where("BatchNo",$bme->escape($param["batchticket"]));
            $bme->set("nwf_stat",$bme->escape('V'));
            $bme->update("PNITEM");

            $msg['query'] = $bme->last_query();
            $msg['msg'] = return_msg('201');
            $msg['msgno'] = 201;
        }else{
            $msg['msg'] = return_msg('101');
            $msg['msgno'] = 101;
        }
        
        

        $this->response($msg, 200);
    }
}
