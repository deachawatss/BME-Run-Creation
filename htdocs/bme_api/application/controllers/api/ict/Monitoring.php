<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With,X-API-KEY");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Monitoring extends RestController
{
	public function __construct() {
        parent::__construct();
	}
	public function temp_post(){
		
		$apikey = $this->input->post('api_key');
		$area = $this->input->post('area');
		$temp = $this->input->post('temp');
		$humid = $this->input->post('humid');

		$toinsert = [
			"area"=>$area,
			"temp"=>$temp,
			"humid"=>$humid,
			"apikey"=>$apikey,
			"created_at"=>date("Y-m-d H:i:s"),
		];

		$this->response($toinsert, 200);
		#echo json_encode($toinsert);
	}

	public function setIP_post(){
		$data = json_decode(file_get_contents('php://input'), true);
		$apikey = $data['API-KEY'];
		#$area = $this->input->post('area');
		#$temp = $this->input->post('temp');
		#$humid = $this->input->post('humid');
		$ip = $data["IP"];



		$dd = [
			"location_ip" => $ip
		];

		$server = $this->load->database("ict_db",true);
		$server->where("apikey='".$apikey."'")->update('monitor_temp_location_ref',$dd);

		$this->response($data, 200);
	}
}
