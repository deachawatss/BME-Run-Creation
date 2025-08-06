<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserAccount_dt extends MY_Model implements DatatableModel{

			
	public function appendToSelectStr() {
        return NULL;
    }
  
    public function fromTableStr() {
        return $this->table;
    }

    public function joinArray() {
        return ['tbl_userlvl | left' => 'tbl_userlvl.userlvlid = tbl_user.ulvl'];
    }

    public function whereClauseArray() {
        return NULL;
    }
}