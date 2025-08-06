<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

 public function __construct()
 {
        parent::__construct();
         $this->load->model("auth_model");
 }

 public function index()
    {
                                #header("Access-Control-Allow-Origin: *");
                                $this->load->view('login');
    }

 public function validate2(){
    
    $uname=$this->input->post("username");
    $pword=$this->input->post("pass");

    $login=$this->auth_model->LDAPUserLogin($uname,$pword);
    $appname = $this->config->item('appname');

    if(!is_null($login)){
        #print_r($login);
        $udata=
        array(
           $appname => array(
                    "userid"=>$login->userid,
                    "uname"=>$login->uname,
                    "pword"=>$login->pword,
                    "ulvl"=>$login->ulvl,
                    "Fname"=>$login->Fname,
                    "Lname"=>$login->Lname,
                    "Position"=>$login->Position,
                    "kae"=>$login->kae,
                    "memberof" => $login->group_name,
                    "department" => $login->dept_code,
                    "section" => $login->sect_code,
            )
        );
        
        $this->session->set_userdata($udata);

        redirect("Home","redirect");
    }else{

        
        $this->load->view('login',array("msg"=>"Invalid Username/Password"));
    }


 }

 public function validate(){
    
    $uname=$this->input->post("username");
    $pword=$this->input->post("pass");

    $login=$this->auth_model->UserLogin($uname,$pword);
    $appname = $this->config->item('appname');
    if(!is_null($login)){
        #print_r($login);
        $udata=
        array(
           $appname => array(
                    "userid"=>$login->userid,
                    "uname"=>$login->uname,
                    "pword"=>$login->pword,
                    "ulvl"=>$login->ulvl,
                    "Fname"=>$login->Fname,
                    "Lname"=>$login->Lname,
                    "Position"=>$login->Position,
            )
        );
        
        $this->session->set_userdata($udata);

        redirect("Home","redirect");
    }else{

        
        $this->load->view('login',array("msg"=>"Invalid Username/Password"));
    }


 }

 public function logout(){
   $appname = $this->config->item('appname');
   unset( $_SESSION[$appname] );
        redirect("Auth","refresh");
 }

 public function info(){
    $conn = odbc_connect("Driver={SQL Server};Server=192.168.1.37,1433;Database=PFCTEST2;", "sa", "Password@123");
        if ($conn) {
            echo "ODBC connection successful";
            odbc_close($conn);
        } else {
            echo "ODBC connection failed";
        }
 }
    
}