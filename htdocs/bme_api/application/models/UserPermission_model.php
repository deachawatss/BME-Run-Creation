<?php
    class Userpermission_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

        public function getColumn($tbl=""){

            $array=array(
                    array("db"=>"userpermission_id","dt"=>0,"th"=>"",
                    "frm"=>array(
                        "type"=>"hidden"    
                    ),
                    "formatter"=>function($d, $row){
                    # return $d
                        $btn="<a class='btn btn-outline-info' onclick='btnUpdate_".$this->datatables->table."(".$d.")'><i class='fas fa-pencil-alt'></i></a>";
                        $btn.="<a class='btn btn-outline-warning' onclick='btnDelete_".$this->datatables->table."(".$d.")'><i class='fas fa-trash'></i></a>";
                        return $btn;
                     }),
                    array("db"=>"userlvlid","dt"=>1,"th"=>"User Level",
                        "frm"=>array(
                            "type"=>"select",
                            "opt"=>$this->models->getref("userlvlid","userlevel","tbl_userlvl")
                        ),
                        "formatter"=>function($d, $row){
                        # return $d
                            return $this->db->select("userlevel")->where("userlvlid",$d)->get("tbl_userlvl")->row()->userlevel;
                         }
                    ),
                    array("db"=>"pageid","dt"=>2,"th"=>"Page",
                        "frm"=>array(
                            "type"=>"select",
                            "opt"=>$this->models->getref("pageid","pagetitle","tbl_page")
                        ),
                        "formatter"=>function($d, $row){
                        # return $d
                            return $this->db->select("pagetitle")->where("pageid",$d)->get("tbl_page")->row()->pagetitle;
                         }
                    ),
                    array("db"=>"list","dt"=>1,"th"=>"LIST",
                    "formatter"=>function($d, $row){
                    # return $d
                        return (intval($d)==0 ? "<input type='checkbox' readonly/>":"<input type='checkbox' checked='checked' readonly/>");
                     }
                    ),
                    array("db"=>"add","dt"=>1,"th"=>"ADD",
                        "formatter"=>function($d, $row){
                        # return $d
                        return (intval($d)==0 ? "<input type='checkbox' readonly/>":"<input type='checkbox' checked='checked' readonly/>");
                         }
                    ),
                    array("db"=>"edit","dt"=>1,"th"=>"EDIT",
                        "formatter"=>function($d, $row){
                        # return $d
                        return (intval($d)==0 ? "<input type='checkbox' readonly/>":"<input type='checkbox' checked='checked' readonly/>");
                         }
                    ),
                    array("db"=>"delete","dt"=>1,"th"=>"DELETE",
                        "formatter"=>function($d, $row){
                        # return $d
                        return (intval($d)==0 ? "<input type='checkbox' readonly/>":"<input type='checkbox' checked='checked' readonly/>");
                         }
                    ),
                );

            return $array;

        }

        public function resetLevel(){
            $userid=$this->input->get('id');
            $toupdate=array(
                'list'=>0,
                'add'=>0,
                'edit'=>0,
                'delete'=>0,
            );
            $this->db->where("userlvlid",$userid)->update("tbl_permission",$toupdate);

        }

        public function updateLevel($pageid="",$param=array()){
            $userid=$this->input->get('id');
            $count=$this->db
                            ->select("count(*) as dd")
                            ->where("pageid",$pageid)
                            ->where("userlvlid",$userid)
                            ->get("tbl_permission")->row()->dd;
                        
            if($count>0){
                
                $this->db
                        ->where("pageid",$pageid)
                        ->where("userlvlid",$userid)
                        ->update("tbl_permission",$param);
            }else{

                $toupdate=array(
                    "list"=>@$param["list"],
                    "add"=>@$param["add"],
                    "edit"=>@$param["edit"],
                    "delete"=>@$param["delete"],
                    "pageid"=>$pageid,
                    "userlvlid"=>$userid,
                );

                $this->db->insert("tbl_permission", $toupdate);


            }


        }
    }
?>
