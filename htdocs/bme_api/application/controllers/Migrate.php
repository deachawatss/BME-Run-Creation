<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
		$this->load->library('migration');

        if ($this->migration->current() === FALSE)
            {
                show_error($this->migration->error_string());
            }
		else{
				/*$this->db->query("
					insert into tbl_userlvl ('userlevel','enter_by','created_by') value ()
				");*/
				
				#tbl_userlvl
				$to_insert_tbl_userlvl=array(
					array(
						"userlvlid"=>100,
						"userlevel"=>"Anonymous",
						"enter_by"=>"admin",
					),
					array(
						"userlvlid"=>1,
						"userlevel"=>"Admin",
						"enter_by"=>"admin",
					),
					array(
						"userlvlid"=>2,
						"userlevel"=>"User",
						"enter_by"=>"admin",
					),
				);
				
				$this->db->insert_batch("tbl_userlvl",$to_insert_tbl_userlvl);
				
				#tbl_user
				$to_insert_tbl_user=array(
					array(
						"userid"=>"100",
						"uname"=>"Anonymous",
						"pword"=>"",
						"ulvl"=>0,
						"Fname"=>"",
						"Lname"=>"",
						"Position"=>"",
						"enter_by"=>"admin",
					),
					array(
						"userid"=>"1",
						"uname"=>"admin",
						"pword"=>"admin",
						"ulvl"=>1,
						"Fname"=>"admin",
						"Lname"=>"admin",
						"Position"=>"Administrator",
						"enter_by"=>"admin",
					),
				);
				
				$this->db->insert_batch("tbl_user",$to_insert_tbl_user);
				
				#tbl_page
				$to_insert_tbl_page=array(
					array(
						"pageid"=>"1",
						"pagename"=>"home",
						"pagetitle"=>"Home",
						"icon"=>"home",
						"menu_0"=>"0",
						"menu_1"=>"0",
						"menu_2"=>"0",
						"menu_order"=>"1",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"2",
						"pagename"=>"#admintools",
						"pagetitle"=>"Admin Tools",
						"icon"=>"tools",
						"menu_0"=>"0",
						"menu_1"=>"0",
						"menu_2"=>"0",
						"menu_order"=>"10",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"3",
						"pagename"=>"useraccount",
						"pagetitle"=>"User Management",
						"icon"=>"users",
						"menu_0"=>"10",
						"menu_1"=>"0",
						"menu_2"=>"0",
						"menu_order"=>"1",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"4",
						"pagename"=>"userlevel",
						"pagetitle"=>"User Level",
						"icon"=>"user-check",
						"menu_0"=>"10",
						"menu_1"=>"0",
						"menu_2"=>"0",
						"menu_order"=>"2",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"5",
						"pagename"=>"page",
						"pagetitle"=>"Page List",
						"icon"=>"file",
						"menu_0"=>"10",
						"menu_1"=>"0",
						"menu_2"=>"0",
						"menu_order"=>"4",
						"enter_by"=>"admin",
					),
				);
				
				$this->db->insert_batch("tbl_page",$to_insert_tbl_page);
			
			
				#tbl_permission
				$to_insert_tbl_permission=array(
					array(
						"pageid"=>"1",
						"userlvlid"=>"1",
						"list"=>"1",
						"add"=>"1",
						"edit"=>"1",
						"delete"=>"1",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"2",
						"userlvlid"=>"1",
						"list"=>"1",
						"add"=>"1",
						"edit"=>"1",
						"delete"=>"1",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"3",
						"userlvlid"=>"1",
						"list"=>"1",
						"add"=>"1",
						"edit"=>"1",
						"delete"=>"1",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"4",
						"userlvlid"=>"1",
						"list"=>"1",
						"add"=>"1",
						"edit"=>"1",
						"delete"=>"1",
						"enter_by"=>"admin",
					),
					array(
						"pageid"=>"5",
						"userlvlid"=>"1",
						"list"=>"1",
						"add"=>"1",
						"edit"=>"1",
						"delete"=>"1",
						"enter_by"=>"admin",
					),
				);
				
				$this->db->insert_batch("tbl_permission",$to_insert_tbl_permission);
			}
    }

}
?>
