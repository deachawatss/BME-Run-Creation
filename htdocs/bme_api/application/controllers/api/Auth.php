<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Auth extends RestController
{
    var $ndb;
    var $bme;
    
	public function __construct() {
        $this->has_enter_by = false;
        parent::__construct();
        
        $this->ndb = $this->load->database('default',true);
       # $this->bme = $this->load->database('nwfth',true);
	}
	public function login_post(){
        $apikey = $this->input->post('api_key');
        $uname = $this->input->post('uname');
		$pword = $this->input->post('pword');

       $userdata = $this->ndb
                ->where('uname',$uname)
                ->where('pword',$pword)
                ->get('tbl_user')->row();
              
        
        if($userdata){


            $sessionid = date('YmdHis').$uname;
            $sessiondata = $this->ndb->where("uname",$uname)->get("tbl_mobile_session")->row();
            if($sessiondata){

                $toupdate = [
                    "sessionid" => $sessionid,
                ];
                $this->ndb->where("id",$sessiondata->id)->update("tbl_mobile_session",$toupdate);
            }else{

                $toinsert = [
                    "uname" => $uname,
                    "sessionid" => $sessionid,
                    "created_at" => date("Y-m-d H:i:s")
                ];

                $this->ndb->insert("tbl_mobile_session",$toinsert);

            }

            $data = [
                "results" =>[
                    'userid' => $userdata->userid,
                    'Fname' => $userdata->Fname,
                    'Lname' => $userdata->Lname,
                    'ulvl' => $userdata->ulvl,
                    'uname' => $userdata->uname,
                    'nwfthsession' => $sessionid,
                ],
                'nwfthsession' => $sessionid,
                "isLogin" => true
            ];
            $this->response($data, 200);
		
        }else{
            $data = [
                "isLogin" => false,
                "msg" => "Login Failed"
            ];
            $this->response($data, 200);
        }

    }
}
