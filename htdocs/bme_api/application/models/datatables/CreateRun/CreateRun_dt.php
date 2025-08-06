<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CreateRun_dt extends MY_Model implements DatatableModel{

			
	public function appendToSelectStr() {
        return NULL;
    }
  
    public function fromTableStr() {
        return "tbl_runhrd";
    }

    public function joinArray() {
        return null;
    }

    public function whereClauseArray() {
        return Null;
    }
}