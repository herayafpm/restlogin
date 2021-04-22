<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'user_id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'user_username'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
				'unique' 		 => true,
			],
			'user_nama'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'user_email'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
				'unique' 		 => true,
			],
			'user_alamat'       => [
				'type'           => 'TEXT',
			],
			'user_no_telp'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'user_aktif'       => [
				'type'           => 'INT',
				'constraint'     => 1,
				'default' => 0
			],
			'user_password'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'user_created_at'       => [
				'type'           => 'TIMESTAMP',
				'default' => date('Y-m-d H:i:s')
			],
			'user_updated_at'       => [
				'type'           => 'TIMESTAMP',
				'default' => date('Y-m-d H:i:s')
			],
		]);
		$this->forge->addKey('user_id', true);
		$this->forge->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}