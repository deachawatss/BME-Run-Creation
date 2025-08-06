<?php

class My_Controller extends CI_Controller
{   
    var $login_details=0;
    var $inp_form=array();
    var $subtable="";
    #var $titlepage="";
    #var $title="";
    var $userinfo;
    var $permission=array();
    var $npermission=array();
    var $pageclass="";
    var $mywhere="";
    var $anonymous=array();
    var $table="";
    var $jsvar="";
    var $addJS="";
    var $frmJS="";
    var $enableLogs=true;
    var $includeJS=[];
    var $includeCSS=[];
    var $pageid="";
    var $cBtn="";
	var $ndb = null;
    var $list_defaults=array(
        'created_at'=>false,
        'date_updated'=>false,
        'date_deleted'=>false,
        'enter_by'=>false,
        'updated_by'=>false,
        'deleted_by'=>false,
    );
    
    var $ref_filter = [];

    var $tblJS="";
    
    #Add Created at
    var $has_created_at=true;
    var $has_updated_date=true;
    var $has_deleted_date=true;
    
    #Add Created at
    var $has_enter_by=true;
    var $has_updated_by=true;
    var $has_deleted_by=true;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('frm');
        $this->load->helper('cpage');
        $this->load->helper('datatable');
        $appname = $this->config->item('appname');
        
		if($this->ndb == null){
			$this->ndb=$this->db;
		}
        
        $this->userinfo=@$this->session->userdata[$appname];

        if( isset($this->session->userdata[$appname]) ){
           $udata = $this->db->where('userid',$this->userinfo['userid'])->get('tbl_user')->row();
           #$this->userinfo['dept_code'] = $udata->dept_code;
           #$this->userinfo['sect_code'] = $udata->dept_code;
        }
		
		if(isset($this->userinfo['ulvl'])){
        
			if(@$this->userinfo['ulvl']!=""){
				#redirect("Auth","refresh");
				$pagearr=array(
					1
				);
			
			}
			
			$cu_class=$this->router->fetch_class();
			$this->pageclass=$cu_class;
		   
			$validuser=[];
		
			if(@$this->userinfo['ulvl']==1 ){#|| $this->userinfo['ulvl']==2){
				$pagearr[]=5;
				$pagearr[]=3;
				$pagearr[]=4;
				$validuser[0]=[];
			}

			$m=$this->db->where("userlvlid",$this->userinfo['ulvl'])
					->join("tbl_page","tbl_page.pageid=tbl_permission.pageid")
					->get('tbl_permission')->result();
					
			$mysec=array();
				if($m){
					foreach($m as $k=>$v){
						$mysec[$v->pagename]=$v;
						$validuser[$v->pageid]=$v;
						$this->npermission[$v->pageid]=$v;
					}
					$this->permission=$mysec;
				}

			$pagename=implode("','",$pagearr);
				
			$m=$this->db->where("tbl_page.pageid in ('".$pagename."')")
						->where('userlvlid',@$this->userinfo['ulvl'])
						->join("tbl_page","tbl_page.pageid=tbl_permission.pageid")
						->get('tbl_permission')->result();
			
			foreach($m as $k=>$v){
				$mysec[$v->pagename]=$v;
				$this->permission[$v->pagename]=$v;
				$validuser[$v->pageid]=$v;
				$this->npermission[$v->pageid]=$v;
			}

			if(!array_key_exists($this->pageid,$validuser)){
				redirect("Auth","redirect");
			}
		
	
		}else{
			redirect("Auth","redirect");
		}


       
    }

    public function page_list(){
        $cu_class=$this->router->fetch_class();
		#$mainmenu=$this->CI->db->order_by("menu_0 asc,menu_1 asc,menu_2 asc, menu_order asc")->get("tbl_page")->result();
        $mainmenu=$this->models->getRef_data("tbl_page","1=1","menu_0 asc,menu_1 asc,menu_2 asc, menu_order asc");

        #$submenu1=$this->CI->db->where("menu_0!='0'")->order_by("menu_0,menu_order asc")->get("tbl_page")->result();
        $menu_array=array();
        
        foreach($mainmenu as $k=>$v){
        
            if(strtoupper($cu_class)==strtoupper($v->pagename) ) {
                $this->titlepage=$v->pagetitle;
                $this->title=($v->icon!="" ? "<i class='fas fa-".$v->icon."'></i>&nbsp;":"").$v->pagetitle;
            }
           
           switch(true){
                
                case ($v->menu_2!=0):{
                    
                    $menu_array[$v->menu_0]["submenu"][$v->menu_1]["submenu"][$v->menu_2]["submenu"][$v->menu_order]=array(
                       "pageid"=>$v->pageid,
                       "pagetitle"=>$v->pagetitle,
                       "icon"=>$v->icon,
                       "pagename"=>$v->pagename
                    );
                    
                    break;
                }
                
                case ($v->menu_1!=0):{
                    
                    $menu_array[$v->menu_0]["submenu"][$v->menu_1]["submenu"][$v->menu_order]=array(
                       "pageid"=>$v->pageid,
                       "pagetitle"=>$v->pagetitle,
                       "icon"=>$v->icon,
                       "pagename"=>$v->pagename
                    );
                    
                    break;
                }
                
                case (($v->menu_0 != 0 ) ) :{
                
                    $menu_array[$v->menu_0]["submenu"][$v->menu_order]=array(
                       "pageid"=>$v->pageid,
                       "pagetitle"=>$v->pagetitle,
                       "icon"=>$v->icon,
                       "pagename"=>$v->pagename
                   );
                    
                    break;
                }
                
                case ($v->menu_0==0):{
                    
                    $menu_array[$v->menu_order]=array(
                       "pageid"=>$v->pageid,
                       "pagetitle"=>$v->pagetitle,
                       "icon"=>$v->icon,
                       "pagename"=>$v->pagename
                   );
                    
                    break;
                }
                
                default:{
                    
                    break;
                }
                
            }
        
        }
        
        return $menu_array;

    }
    
    public function sendMobile($mobile="/topics/all",$title="",$info="",$body=""){
        define( 'API_ACCESS_KEY', 'AAAAJzmRU5A:APA91bGIkXSidq3a8h6XwCedb5x8naVb8NZUHX3szpW3EwpmjKdiPQnEW0fJfExV8lxgHeG_SxrMgA2XpaAlyccFihZmoBzaZ83bNgJnMqDH84-nXPo95eUJXsprmQ3ZXhm7nEuBptAQ' ); 

        $fields = array(
            'to'    => $mobile,
            'data'  => array(
                                "title"=> $title,  
                                "msg"=>$body,
                                "datetime"=>date("Y-m-d H:i:s"), 
                                //"icon"         => "fcm_push_icon"  // White icon Android resource 
            ),
            'notification' => array(
                                "title"        => $title,  //Any value 
                                "body"         => $info,  //Any value 
                                "color"        => "#666666",
                                "sound"        => "default", //If you want notification sound 
                                "click_action" => "FCM_PLUGIN_ACTIVITY",  // Must be present for Android 
                                "icon"         => "fcm_push_icon"  // White icon Android resource 
                            )
        );


        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        //~ curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        #echo $result;

    }


    public function page_load(){
        
        $model=$this->router->fetch_class()."_model";

		if($model != "UserPermission_model")
			 $model=@$this->npermission[$this->pageid]->pagename."_model";

        $cu_class=$this->router->fetch_class();
        if(file_exists(APPPATH."models/".$model.".php")){
             $this->load->model($model,"models");
         }else{
             $this->load->model("Default_model","models");
             #$this->my_model->my_fcn($prams);
             
         }
         $this->models->permission=@$this->permission[$cu_class];
    
            
    }
        
    public function load_menu(){
                    $cu_class=$this->router->fetch_class();
         #$mainmenu=$this->CI->db->order_by("menu_0 asc,menu_1 asc,menu_2 asc, menu_order asc")->get("tbl_page")->result();
		 			$tmpdb = $this->ndb;
		 			$this->ndb=$this->db;
                    $mainmenu=$this->models->getRef_data("tbl_page","1=1","menu_0 asc,menu_1 asc,menu_2 asc, menu_order asc");
                    $this->ndb=$tmpdb;
                    #$submenu1=$this->CI->db->where("menu_0!='0'")->order_by("menu_0,menu_order asc")->get("tbl_page")->result();
                    $menu_array=array();
                    
                    foreach($mainmenu as $k=>$v){
                    
                        if(strtoupper($cu_class)==strtoupper($v->pagename) ) {
                            $this->titlepage=$v->pagetitle;
                            $this->title=($v->icon!="" ? "<i class='fas fa-".$v->icon."'></i>&nbsp;":"").$v->pagetitle;
                        }
                       
                       if(@$this->permission[$v->pagename]->list && $v->display == "Y") 
                         switch(true){
                            
                            case ($v->menu_2!=0):{
                                
                                $menu_array[$v->menu_0]["submenu"][$v->menu_1]["submenu"][$v->menu_2]["submenu"][$v->menu_order]=array(
                                "pageid"=>$v->pageid,
                                "pagetitle"=>$v->pagetitle,
                                "icon"=>$v->icon,
                                "pagename"=>$v->pagename
                                );
                                
                                break;
                            }
                            
                            case ($v->menu_1!=0):{
                                
                                $menu_array[$v->menu_0]["submenu"][$v->menu_1]["submenu"][$v->menu_order]=array(
                                "pageid"=>$v->pageid,
                                "pagetitle"=>$v->pagetitle,
                                "icon"=>$v->icon,
                                "pagename"=>$v->pagename
                                );
                                
                                break;
                            }
                            
                            case (($v->menu_0 != 0 ) ) :{
                            
                                $menu_array[$v->menu_0]["submenu"][$v->menu_order]=array(
                                "pageid"=>$v->pageid,
                                "pagetitle"=>$v->pagetitle,
                                "icon"=>$v->icon,
                                "pagename"=>$v->pagename
                                );
                                
                                break;
                            }
                            
                            case ($v->menu_0==0):{
                                
                                $menu_array[$v->menu_order]=array(
                                "pageid"=>$v->pageid,
                                "pagetitle"=>$v->pagetitle,
                                "icon"=>$v->icon,
                                "pagename"=>$v->pagename
                                );
                                
                                break;
                            }
                            
                            default:{
                                
                                break;
                            }
                            
                          }
                        
                    }
                    
                    
                    
                    return $menu_array;
    
    }
    
    public function load_security(){
        
    }

    public function addData(){
        $msg = [];
        if($this->npermission[$this->pageid]->add)
            if($this->table!=""){
                $mdata=$this->input->post();

                foreach($mdata as $k=>$v){
				
                    if(is_array($v))
                    $mdata[$k]=json_encode($v);

                    if(str_contains($k,'pop_'))
                        unset($mdata[$k]);
                
                }

                //before Add
                if(method_exists($this,"before_insert")){
                    $mdata = $this->before_insert($mdata);
                }

                #$id[$this->pkey] = $mdata[$this->pkey];

                unset($mdata[$this->pkey]);
                $newkey = $this->models->Add($this->table,$mdata);

                //after Add
                if(method_exists($this,"after_insert")){
                    $mdata = $this->after_insert($mdata,$newkey);
                    $msg['newdata'] = $newkey;
                }


            }
            echo json_encode($msg);
        
    }

    public function copyData(){
        if($this->npermission[$this->pageid]->add)
            if($this->table!=""){
                $mdata=$this->input->post();

                foreach($mdata as $k=>$v){
				
                    if(is_array($v))
                    $mdata[$k]=json_encode($v);

                    if(str_contains($k,'pop_'))
                        unset($mdata[$k]);
                
                }

                $pkey=$mdata[$this->pkey];
                //before Add
                if(method_exists($this,"before_copy")){
                    $mdata = $this->before_copy($mdata,$pkey);
                }

                unset($mdata[$this->pkey]);
                
                $newkey = $this->models->Add($this->table,$mdata);

                //after Add
                if(method_exists($this,"after_copy")){
                    $mdata = $this->after_copy($mdata,$newkey);
                }


            }
            echo json_encode(array());
        
    }

    public function updateData(){
        if($this->npermission[$this->pageid]->edit)
            if($this->table!=""){
                $mdata=$this->input->post();

                foreach($mdata as $k=>$v){
				
                    if(is_array($v))
                    $mdata[$k]=json_encode($v);

                    if(str_contains($k,'pop_'))
                        unset($mdata[$k]);
                
                }

                $pkey=$mdata[$this->pkey];

                if(method_exists($this,"before_update")){
                    $mdata = $this->before_update($mdata,$pkey);
                }

                
                unset($mdata[$this->pkey]);
                $this->models->Update($this->table,$mdata,$this->pkey."='".$pkey."'");
                
                if(method_exists($this,"after_update")){
                    $mdata = $this->after_update($mdata,$pkey);
                }

            }
            echo json_encode(array());
        
    }

    public function getInfo(){
        $id=$this->input->post("id");
        $mdata=$this->models->getInfo($this->table,$this->pkey."='".$id."'");
        $pop_updata = [];
        $mycols_param = $this->models->getColumn();

        foreach($mycols_param as $k => $v){
            
            if(isset($v['type']) && $v['type'] == 'pop_up'){
                $mycol = explode('.',$v['db'])[1];
                $field_ref_label = $v['ajax']['text'];
                #echo $v['ajax']['id'];
                #print_r($v['db']) ;
                $msg = $this->ndb->where($v['ajax']['id'] , $mdata->$mycol)->get($v['ajax']['table'])->row();

                if(isset($msg->$field_ref_label))
                    $msg = $msg->$field_ref_label;

                $pop_updata[ $mycol ] = $msg;
            }

        }


        echo json_encode(array("data"=>$mdata,"id"=>['key' => $this->pkey , 'val' => $id], "pop_updata" => $pop_updata));
    }
    
    public function deleteData(){
        $mdata=[];
        if($this->npermission[$this->pageid]->delete){
            $id=$this->input->post("id");

            if(method_exists($this,"before_delete")){
                $mdata = $this->before_delete($mdata,$id);
            }
        
            $mdata=$this->models->Delete($this->table,$this->pkey."='".$id."'");

            if(method_exists($this,"after_delete")){
                $mdata = $this->after_delete($mdata,$id);
            }
            
        }
        echo json_encode($mdata);
        
    }

    public function addDataSub(){
        if($this->subdb->table!=""){
            $mdata=$this->input->post();
            unset($mdata[$this->subdb->pkey]);
            $this->models->Add($this->subdb->table,$mdata);
            echo json_encode(array());
        }
        
    }

    public function updateDataSub(){
        if($this->subdb->table!=""){
            $mdata=$this->input->post();
            $pkey=$mdata[$this->subdb->pkey];
            unset($mdata[$this->subdb->pkey]);
            $this->models->Update($this->subdb->table,$mdata,$this->subdb->pkey."='".$pkey."'");
            echo json_encode(array());
        }
        
    }

    public function getInfoSub(){
        $id=$this->input->post("id");
        $mdata=$this->models->getInfo($this->subdb->table,$this->subdb->pkey."='".$id."'");
        echo json_encode($mdata);
    }
    
    public function deleteDataSub(){
        $id=$this->input->post("id");
        
        $mdata=$this->models->Delete($this->subdb->table,$this->subdb->pkey."='".$id."'");
        echo json_encode($mdata);
    }

    public function loadSData(){
        $x=$this->datatables->simple();
        echo json_encode($x);
    }

    public function loadCData(){
        $x=$this->datatables->complex();
        echo json_encode($x);
    }

    public function loadSDataSub(){
       $xid=$this->input->get("id");
        $x=$this->subdb->simple( $this->subdb->fkey."='".$xid."'");
        echo json_encode($x);
        
    }

    public function SendEmail($recient,$title="",$msg="",$cc="",$app_user='cemail'){
        $uname=config_item($app_user)['username'];
        $pword=config_item($app_user)['password'];
        $this->load->library('email');
        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['crlf'] = "rn";
        $config['priority'] = 3;
        $config['smtp_host'] = config_item($app_user)['smtp_host'];
        $config['smtp_port'] = config_item($app_user)['smtp_port'];
        #$config['smtp_crypto'] = config_item('cemail')['smtp_crypto'];
        $config['smtp_user'] = $uname;
        $config['smtp_pass'] = $pword;
        $config['smtp_timeout'] = 5;
        $config['newline'] = "\r\n";
        $this->email->initialize($config);
            
        $this->email->set_newline("\r\n");
        $this->email->from($uname);
        $this->email->to($recient);
        if($cc!=""){
            $this->email->cc($cc);
        }
            
            
        $this->email->subject($title);
        $this->email->message($msg);
            
        $this->email->send();
        
    }
    
    public function dataTable() {
        if(!isset($this->table) && !isset($this->pkey) ){
            show_404();
        }
        
        #$dt=$this->router->fetch_class()."_dt";
        $dt=$this->npermission[$this->pageid]->pagename."_dt";
        if(file_exists(APPPATH."models/datatables/".$dt.".php")){
             $this->load->library('Datatable', ['model' => 'datatables/'.$dt, 'rowIdCol' => $this->table.'.'.$this->pkey, "table"=> $this->table ] );
         }else{
             $this->load->library('Datatable', ['model' => 'datatables/default_dt', 'rowIdCol' => $this->table.'.'.$this->pkey, "table"=> $this->table]);
             #$this->my_model->my_fcn($prams);
             
         }
         
         foreach($this->models->getColumn() as $k => $v){
            $this->datatable->setColumnSearchType($v["db"], 'both');
         }

         $this->datatable->getColumnSearchType('section.section');
         $this->datatable->setPreResultCallback(function(&$json){
                $rows =& $json['data'];
                $CI =& get_instance();
                $mycols = [];
				$dt=$this->router->fetch_class();

                if(method_exists($CI->models,'setFormatter'))
                    $mycols=$CI->models->setFormatter();

                $mycols_param=$CI->models->getColumn();
                
               # print_r($mycols_param);
                #die();
                $x=[];
                foreach($rows as &$r) {
                    
                    #$r[$this->table][$this->pkey]="add,edit,delete";
                    
                    
                    if(is_array($r)){
                        
                        foreach($r as $k=>$v){

                           

                            if( is_array($v) ){
                                //$x.="true1";
                                $dta_count = 0;
                                foreach($v as $kk=>$vv){
                                
                                    
                                    if(is_callable(@$mycols[$k][$kk]) ){
                                        $r[$k][$kk] = @$mycols[$k][$kk]($vv,$v , $mycols_param[$dta_count]);
                                        
                                    }else{
                                    
                                        if($k == $this->table && $this->pkey == $kk){
                                        
                                            /*Set default value for primary key add, edit, delete*/
                                        
                                                $r[$k][$kk]="";
                                                if(@$this->npermission[$this->pageid]->edit)
                                                    $r[$k][$kk].= "<a class='btn btn-outline-info btn-edit-".$this->table."'  data-id='".$vv."' ><i class='fas fa-pencil-alt'></i></a>";
                                                if(@$this->npermission[$this->pageid]->delete)
                                                    $r[$k][$kk].= "<a class='btn btn-outline-danger btn-delete-".$this->table."' data-id='".$vv."'><i class='fas fa-trash'></i></a>";
                                        
                                            /****************/
                                            
                                        }else{
                                            $r[$k][$kk]=$vv;
                                        }
                                    
                                    }

                                    $dta_count++;
                                        
                                }
                                
                            }else{
                                $r[$k] = @$mycols[$k] ?? $v;
                            }
                            
                        }
                        
                    }else{
                    
                    }
                    
                }
                
                
                
                
                #$json['xx'] = $this->models->permission;	
             
             });
         
        $this->output->set_header("Pragma: no-cache")
            ->set_header("Cache-Control: no-store, no-cache")
            ->set_content_type('application/json')
            ->set_output(json_encode($this->datatable->datatableJson()));
    }
        
    public function genJS(){
        
    }

    /*
        $lookup_ = [
            "row_id" => [
                "ajax" => [
                    "filter_field" => "val",
                    "id" => "",
                    "text" => "",
                    "display" => [],
                    "table"=> "",
                    "list_type"=> "select || popup",
                    "conn" => $this->ndb,
                    "force_val"=>"" #force Value for fileter
                ]
            ]

        ]
    */
    public function lookup(){

        
        $param = $this->input->post();
        $lookup = $this->models->getColumn();

        $msg = [];

        if(!isset($param['lookup'])){
            show_404();
        }else{

            if( isset( $lookup[ $param['lookup'] ]['ajax'] ) ){
                $nlookup = $lookup[ $param['lookup'] ]['ajax'];
                $toselect = "*";
                $todisplay_array = [];
                $todisplay = "";
                $header = [];
    
                if(!isset($param['parent_val'])){
                    $param['parent_val'] = '';
                }
    
                if(isset($lookup[ $param['lookup'] ]['force_val'])){
                    $param['parent_val'] = $lookup[ $param['lookup'] ]['force_val'];
                }
    
                $db = ( isset($nlookup['conn']) ? $nlookup['conn'] : $this->ndb );
               
                $where = "1=1";
    
                if( isset( $nlookup["filter_field"] ) && $param['parent_val'] != ''){
                   $where = $nlookup["filter_field"]." = '".$param['parent_val']."'";
                }
    
                if( !isset($nlookup['list_type']) || $nlookup['list_type'] == "select" ){
                    $toselect = $nlookup["text"]." as text , ".$nlookup["id"]." as id";
                }else{
    
                    if( isset($nlookup['display']) ){
    
                        foreach($nlookup['display'] as $k => $v){
                            $todisplay_array[] = $v." ";
                            $header["title"] = $k;
                        }
                        $toselect = implode(",",$todisplay_array);
                    }
    
                }
    
               $query = "Select ".$toselect. " from ".$nlookup['table']." where ".$where;
                $msg = $db->query($query)->result();
                echo json_encode( $msg );
            }else{
    
                show_404();
            }

        }

        
        
    }

    public function select2filter(){
        $filter = $this->input->post('filterid');
        $fvalue = $this->input->post('filtervalue');

        if(!isset($this->ref_filter[$filter])){
            show_404();
        }else{
            $myfilter = $this->ref_filter[$filter];
            $mdata = $this->models->getRefSelect2($myfilter['id'],$myfilter['val'],$myfilter['tbl'],$filter." = '".$fvalue."'");
            echo json_encode(['msgcode'=>'200', 'msgdata' => $mdata ]);
        }
    }
        
    
    
}
    
    
    
?>
<?php

?>