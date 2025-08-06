<?php
    class UserLevel_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function getColumn($tbl=""){
            $array=[
				
				[
                    "db"=>"tbl_userlvl.userlvlid",
                    "th"=>"Userlevel",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_userlvl.userlevel",
                    "th"=>"Userlevel",
                    "req"=>true,
                ],
            ];

            return $array;
        }

		public function setFormatter(){
            
            $array = [
                        "tbl_userlvl"=>[
                            
                            "userlvlid"=> function($d,$row){
                               # $d = "<a class='btn btn-outline-success' id='".$row[$d]."'> hey</a>";
                               # return $d;
                               
                                if(@$this->permission->edit)
                                     $btn="<a class='btn btn-outline-info btn-edit-".$this->table."'  data-id='".$d."' ><i class='fas fa-pencil-alt'></i></a>";
                        
                                if(@$this->permission->delete)
                                    $btn.="<a class='btn btn-outline-danger btn-delete-".$this->table."' data-id='".$d."'><i class='fas fa-trash'></i></a>";
                                
									$btn.="<a class='btn btn-outline-primary' href='".base_url()."UserPermission?id=$d'><i class='fas fa-users-cog'></i> PERMISSION</a>";
                        
                                return $btn;
                           }
                        ],
                       /* "tbl_userlvl"=> [
                            "userlevel" => function($d,$row){
                                $d = "<a class='btn btn-outline-success' id='".$row[$d]."'> hey</a>";
                                return $d;
                           }
                        ]*/
                        
                    ];
                    
            return $array;
            
            
        }

	}
?>
