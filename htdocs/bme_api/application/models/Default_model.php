<?php
    class Default_model extends MY_Model {
        
        function __construct()
        {
            parent::__construct();
            
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

        public function getSubColumn(){

            $mycol_array=array();
            $mycol=$this->ndb
                        ->query("Select COLUMN_NAME,COLUMN_KEY,EXTRA,COLUMN_COMMENT from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME ='".$this->subdb->table."'")
                        ->result();
            
            
            foreach($mycol as $k=>$v){
                
                $mycol_array[$k]=array(
                    "db"=>$v->COLUMN_NAME,
                    "dt"=>$k,
                    "th"=>($v->COLUMN_COMMENT != "" ? $v->COLUMN_COMMENT : $v->COLUMN_NAME)
                );

                if($v->COLUMN_KEY == "PRI" && $v->EXTRA=="auto_increment"){
                    $mycol_array[$k]["th"]="";
                    $mycol_array[$k]["frm"]=array("type"=>"hidden");
                    $mycol_array[$k]["formatter"]=function($d, $row){
                                    # return $d;
            
                                    $btn="<a class='btn btn-outline-info' onclick='btnUpdate_".$this->subdb->table."(".$d.")'><i class='fas fa-pencil-alt'></i></a>";
                                    $btn.="<a class='btn btn-outline-warning' onclick='btnDelete_".$this->subdb->table."(".$d.")'><i class='fas fa-trash'></i></a>";
                                    
                                    return @$btn;
            
                    };
                }

            }
            return $mycol_array;

        }
    
		public function setFormatter(){
		
			return [];
		}
	
	} 
?>
