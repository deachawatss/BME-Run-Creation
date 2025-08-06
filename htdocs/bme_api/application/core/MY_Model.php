<?php

class My_Model extends CI_Model
{   
    var $permission;
    public function __construct() {
        parent::__construct();
    }

    public function getRef($value="",$label="",$table="",$where="1=1",$orderby=""){
        if(strpos($label,'concat')){
            $label="`".$label."`";
        }
        return $this->ndb->select("`".$value."` as value , ".$label." as  label")->where($where)->order_by($orderby)->get($table)->result();

    }

    public function getRefSelect2($value="",$label="",$table="",$where="1=1",$orderby="",$db=false){
        if(strpos($label,'concat')){
            $label="`".$label."`";
        }

		if($db==false){
			$db = $this->ndb;
		}

        return $db->select("".$value." as id , ".$label." as  text")->where($where)->order_by($orderby)->get($table)->result();

    }

    public function getRef_data($table="",$where="1=1",$orderby=""){

        return $this->ndb->where($where)->order_by($orderby)->get($table)->result();

    }

    public function getRef_cdata($table="",$select="*",$where="1=1",$orderby="",$group_by=""){

        return $this->ndb
                    ->select($select)
                    ->where($where)
                    ->order_by($orderby)
                    ->group_by($group_by)
                    ->get($table)->result();

    }

    public function getInfo($table,$param){
        return $this->ndb->where($param)->get($table)->row();
    }
    

    public function Add($table="",$param=array()){
		$created_at=date("Y-m-d H:i:s");

		if($this->has_enter_by)
			$param['enter_by'] = $this->userinfo['userid'];
		
		if($this->has_created_at)
			$param['created_at'] = date("Y-m-d H:i:s");

		if($this->ndb->dbdriver == 'sqlsrv')
		foreach($param as $k => $v){
			$param[$k] = $this->ndb->escape($v);
		}

		$this->ndb->insert($table,$param);
		$lastquery = $this->ndb->last_query();
		$last = $this->ndb->insert_id();
		
		if($this->enableLogs){
			$logs=[
				"reported_by"=>$this->userinfo["userid"],
				"created_at"=>$created_at,
				"table"=>$table,
				"old_value"=>"",
				"new_value"=>json_encode($param),
				"action"=>"Add",
				"query"=>$lastquery,
			];

			$this->db->insert("tbl_audit_log",$logs);
		}

        return $last;

    }

    public function Update($table="",$param=array(),$where="1=1"){
		$oldvalue = $this->ndb->where($where)->get($table)->row();

		if($this->has_updated_by)
			$param['updated_by'] = $this->userinfo['userid'];
		
		if($this->has_updated_date)
			$param['date_updated'] = date("Y-m-d H:i:s");
		
		if($this->ndb->dbdriver == 'sqlsrv')
			foreach($param as $k => $v){
				$param[$k] = $this->ndb->escape($v);
			}

        $this->ndb->where($where)->update($table,$param);
		$lastquery = $this->ndb->last_query();
		if($this->enableLogs){
			
			$logs=[
				"reported_by"=>$this->userinfo["userid"],
				"created_at"=>date("Y-m-d H:i:s"),
				"table"=>$table,
				"old_value"=>json_encode($oldvalue),
				"new_value"=>json_encode($param),
				"action"=>"Update",
				"query"=>$lastquery,
			];

			$this->db->insert("tbl_audit_log",$logs);
		}
        
    }

    public function Delete($table="",$where="1=2"){
        $oldvalue = $this->ndb->where($where)->get($table)->row();
        $this->ndb->where($where)->delete($table);
		$lastquery = $this->ndb->last_query();
		if($this->enableLogs){
			$logs=[
				"reported_by"=>$this->userinfo["userid"],
				"created_at"=>date("Y-m-d H:i:s"),
				"table"=>$table,
				"old_value"=>json_encode($oldvalue),
				"new_value"=>"",
				"action"=>"Delete",
				"query"=>$lastquery,
			];

			$this->db->insert("tbl_audit_log",$logs);
		}
    }

    public function popUpSearch($table,$param="*",$where="1=1"){
        
        if(is_array($param)){
            $param=implode(",",$param);
        }

        return $this->ndb->select($param)->where($where)->get($table)->result();

    }

	public function getColumn($tbl=""){

		$mycol=array();
		$mydb="";
		
		#TABLE_SCHEMA='".$this->db->database."' and TABLE_NAME ='".$this->datatables->table."
		if($tbl=="")
			$tbl=$this->ndb->database;
		
		$mydb=$this->ndb->database;
			
		$mycol_array=array();
		
		$mydb=$this->ndb->database;
		
		$mycol=$this->ndb
					->query("Select COLUMN_NAME,TABLE_NAME,COLUMN_COMMENT,DATA_TYPE from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME ='".$tbl."' and TABLE_SCHEMA='".$mydb."'")
					->result();
		
		
		
		foreach($mycol as $k=>$v){
			
			if( array_key_exists( strtolower($v->COLUMN_NAME), $this->list_defaults ) ){
				
				if(  $this->list_defaults[$v->COLUMN_NAME] != false  ){
					$optheader="Action";
					if( !($v->COLUMN_NAME == $this->pkey) ){
						$optheader=(@$v->COLUMN_COMMENT != "" ? $v->COLUMN_COMMENT : $v->COLUMN_NAME);
					}
				
					$mycol_array[]=array(
						"db"=>$v->TABLE_NAME.".".$v->COLUMN_NAME,
						#"dt"=>$k,
						"th"=>$optheader,
						"type"=>$v->DATA_TYPE
					);
					
				}
				
			}else{
				$optheader="Action";
				if( !($v->COLUMN_NAME == $this->pkey) ){
					$optheader=(@$v->COLUMN_COMMENT != "" ? $v->COLUMN_COMMENT : $v->COLUMN_NAME);
				}
				
				$mycol_array[]=array(
						"db"=>$v->TABLE_NAME.".".$v->COLUMN_NAME,
						#"dt"=>$k,
						"th"=>$optheader,
						"type"=>$v->DATA_TYPE
				);
				
				
			
			}
			
			

		}
		
		$this->column=$mycol_array;
		
		return $mycol_array;

	}


}
?>
