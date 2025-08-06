<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Monitoring extends CI_Controller {


 public function __construct()
 {
        parent::__construct();
 }

 public function index()
    {
		$cpr = $this->load->database('cpr',true);
		
		$cprdata = $cpr->where('cpr_expiration between "'.date("Y-m-d").'" and "'.date("Y-m-d",strtotime("+180 day")).'"')
						->get('tbl_cpr')->result();
						
		if(count($cprdata) > 0)	{
			$msg = "Good day, <br/>";
			$msg .= "Please see attached list for CPR RENEWAL.<br/>";
			$msg .= '<table border=1>';
			$msg .= '<thead>';
				$msg .= '<th>FDA REG NO</th>';
				$msg .= '<th>PRODUCT NAME</th>';
				$msg .= '<th>DATE RENWED</th>';
				$msg .= '<th>DATE EXPIRATION</th>';
			$msg .= '</thead>';
			
			foreach($cprdata as $k=>$v){
				$msg .= '<tr>';
				$msg .= '<td>'.$v->cpr_fda_reg_no.'</td>';
				$msg .= '<td>'.$v->cpr_product_name.'</td>';
				$msg .= '<td>'.$v->cpr_date_renew.'</td>';
				$msg .= '<td>'.$v->cpr_expiration.'</td>';
				$msg .= '</tr>';
			}
			
		
			$msg .= '</table><br/><br/>';
			
			$msg .= "<i>Note: This is system generated message. for any concern please message aposadas@newlywedsfoods.com.ph</i>";
		
			$recient = 'ymamalayan@newlywedsfoods.com.ph,aposadas@newlywedsfoods.com.ph';
			$title = 'CPR FOR RENEWAL';
			$this->SendEmail($recient,$title,$msg);

		}			
		
		
		
	}

 private function SendEmail($recient,$title="",$msg="",$cc=""){
        $uname=config_item('cpr_email')['username'];
        $pword=config_item('cpr_email')['password'];
        $this->load->library('email');
        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['crlf'] = "rn";
        $config['priority'] = 3;
        $config['smtp_host'] = config_item('cpr_email')['smtp_host'];
        $config['smtp_port'] = config_item('cpr_email')['smtp_port'];
        #$config['smtp_crypto'] = config_item('cemail')['smtp_crypto'];
        $config['smtp_user'] = $uname;
        $config['smtp_pass'] = $pword;
        $config['smtp_timeout'] = 5;
        $config['newline'] = "\r\n";
        $this->email->initialize($config);
            
        $this->email->set_newline("\r\n");
        $this->email->from($uname);
        $this->email->to($recient);
		#$this->email->reply_to('aposadas@newlywedsfoods.com.ph');
        if($cc!=""){
            $this->email->cc($cc);
        }
            
            
        $this->email->subject($title);
        $this->email->message($msg);
            
        $this->email->send();
        
    }

}