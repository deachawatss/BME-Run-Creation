<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Default_dt extends MY_Model implements DatatableModel{

			
	public function appendToSelectStr() {
        return NULL;
    }
  
    public function fromTableStr() {
        return $this->table;
    }

    public function joinArray() {
        return NULL;
    }

    public function whereClauseArray() {
        return NULL;
    }
}