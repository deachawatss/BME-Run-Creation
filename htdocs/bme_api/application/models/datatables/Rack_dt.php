<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rack_dt extends MY_Model implements DatatableModel{

			
	public function appendToSelectStr() {
        return NULL;
    }
  
    public function fromTableStr() {
        return $this->table;
    }

    public function joinArray() {
        return ['room_tbl | left' => 'room_tbl.room_id = rack_tbl.room_id'];
    }

    public function whereClauseArray() {
        return NULL;
    }
}