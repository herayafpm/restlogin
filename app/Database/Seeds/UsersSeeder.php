<?php

namespace App\Database\Seeds;

class UsersSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {
    // Users
    $password = password_hash("123456", PASSWORD_DEFAULT);
    $initDatas = [
      [
        'user_nama'       => "heraya",
        'user_email'       => "herayafpm@gmail.com",
        'user_username'       => "heraya",
        'user_alamat'       => "banjarnegara",
        'user_no_telp'       => "0",
        'user_aktif'       => 1,
        'user_password'       => $password,
      ],
    ];

    $this->db->table('users')->insertBatch($initDatas);
  }
}