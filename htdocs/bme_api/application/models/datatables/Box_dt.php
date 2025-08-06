<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Box_dt extends MY_Model implements DatatableModel{

			
	public function appendToSelectStr() {
        return NULL;
    }
  
    public function fromTableStr() {
        return $this->table;
    }

    public function joinArray() {
        return [
                'room_tbl | left' => 'room_tbl.room_id = box_tbl.room_id',
                'rack_tbl | left' => 'rack_tbl.rack_id = box_tbl.rack_id'
            ];
    }

    public function whereClauseArray() {
        return NULL;
    }
}