<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_db extends CI_Migration  {

	public function up() {

		#tbl_user
		$this->dbforge->add_field(array(
			'userid' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
				'null' => false,
				'comment' => "User Level ID",
			),
			'uname' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => false,
				'comment' => "Username",
			),
            'pword' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => false,
				'comment' => "Password",
			),
			'ulvl' => array(
				'type' => 'int',
				'constraint' => 11,
				'null' => true,
				'comment' => "User Level",
			),
			'Fname' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'null' => true,
				'comment' => "Firstname",
			),
			'Lname' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'null' => true,
				'comment' => "Surname",
			),
			'Position' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'null' => true,
				'comment' => "Position",
			),
			'enter_by' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'comment' => "Enter By",
			),
			'created_at datetime default current_timestamp',
		));
		$this->dbforge->add_key('userid', TRUE);
		$this->dbforge->create_table('tbl_user');


		#tbl_userlvl
		$this->dbforge->add_field(array(
			'userlvlid' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
				'null' => false,
				'comment' => "User Level ID",
			),
			'userlevel' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => false,
				'comment' => "User Level",
			),
			'enter_by' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'comment' => "Enter By",
			),
			'created_at datetime default current_timestamp',
		));
		$this->dbforge->add_key('userlvlid', TRUE);
		$this->dbforge->create_table('tbl_userlvl');

		#tbl_permission
		$this->dbforge->add_field(array(
			'userpermission_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
				'null' => false,
				'comment' => "User Permission Id",
			),
			'pageid' => array(
				'type' => 'INT',
				'null' => false,
				'comment' => "Page Id",
			),
			'userlvlid' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'comment' => "User Level",
			),
			'list' => array(
				'type' => 'CHAR',
				'constraint' => 1,
				'comment' => "List",
			),
			'add' => array(
				'type' => 'CHAR',
				'constraint' => 1,
				'comment' => "Add",
			),
			'edit' => array(
				'type' => 'CHAR',
				'constraint' => 1,
				'comment' => "Edit",
			),
			'delete' => array(
				'type' => 'CHAR',
				'constraint' => 1,
				'comment' => "Delete",
			),
			'enter_by' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'comment' => "Enter by",
			),
			'created_at datetime default current_timestamp',
		));
		$this->dbforge->add_key('userpermission_id', TRUE);
		$this->dbforge->create_table('tbl_permission');

		#tbl_page
		$this->dbforge->add_field(array(
			'pageid' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
				'null' => false,
				'comment' => "Page id",
			),
			'pagename' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'comment' => "Page Name",
			),
			'pagetitle' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'comment' => "Page Title",
			),
			'icon' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'comment' => "Icon",
			),
			'menu_0' => array(
				'type' => 'INT',
				'comment' => "Main Menu",
			),
			'menu_1' => array(
				'type' => 'INT',
				'comment' => "Sub Menu",
			),
			'menu_2' => array(
				'type' => 'INT',
				'comment' => "Sub Menu 2",
			),
			'menu_order' => array(
				'type' => 'INT',
				'comment' => "Order",
			),
			'enter_by' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'comment' => "Enter by",
			),
			'created_at datetime default current_timestamp',
		));
		$this->dbforge->add_key('pageid', TRUE);
		$this->dbforge->create_table('tbl_page');
	}

	public function down()
	{
		$this->dbforge->drop_table('tbl_user', true);
		$this->dbforge->drop_table('tbl_userlvl', true);
		$this->dbforge->drop_table('tbl_permission', true);
		$this->dbforge->drop_table('tbl_page', true);
	}
}
