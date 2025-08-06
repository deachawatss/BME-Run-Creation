<?php
    class UserAccount_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

        public function getColumn($tbl=""){


            $array=[
                [
                    "db"=>"tbl_user.userid",
                    "th"=>"Action",
                ],
                [
                    "db"=>"tbl_user.uname",
                    "th"=>"Username",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_user.pword",
                    "th"=>"Password",
					"req"=>true,
                ],
                [
                    "db"=>"tbl_userlvl.userlevel",
                    "th"=>"User Level",
                    "odb"=>"ulvl",
                    "type"=>"select",
                    "ref"=> $this->models->getRefSelect2("userlvlid","userlevel","tbl_userlvl")
                ],
                [
                    "db"=>"tbl_user.Fname",
                    "th"=>"Firstname",
                ],
                [
                    "db"=>"tbl_user.Lname",
                    "th"=>"Lastname",
                ],
            ];

            return $array;

        }
        
        public function setFormatter(){
            
            $array = [
                        "tbl_user"=>[
                            
                            "userid"=> function($d,$row){
                               # $d = "<a class='btn btn-outline-success' id='".$row[$d]."'> hey</a>";
                               # return $d;
                               
                                if(@$this->permission->edit)
                                     $btn="<a class='btn btn-outline-info btn-edit-".$this->table."'  data-id='".$d."' ><i class='fas fa-pencil-alt'></i></a>";
                        
                                if(@$this->permission->delete)
                                    $btn.="<a class='btn btn-outline-danger btn-delete-".$this->table."' data-id='".$d."'><i class='fas fa-trash'></i></a>";
                                	
                                return $btn;
                           },
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
