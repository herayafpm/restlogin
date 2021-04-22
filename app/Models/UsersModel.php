<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $DBGroup              = 'default';
	protected $table                = 'users';
	protected $primaryKey           = 'user_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['user_nama', 'user_username', 'user_email', 'user_alamat', 'user_no_telp', 'user_aktif', 'user_password'];

	// Dates
	protected $useTimestamps = true;
	protected $dateFormat           = 'datetime';
    protected $createdField  = 'user_created_at';
    protected $updatedField  = 'user_updated_at';
	protected $deletedField         = '';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
    protected $beforeInsert = ['hashPassword'];
	protected $afterInsert          = [];
    protected $beforeUpdate = ['hashPassword'];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
  protected function hashPassword(array $data)
  {

    if (!isset($data['data']['user_password'])) return $data;
    $data['data']['user_password'] = password_hash($data['data']['user_password'], PASSWORD_DEFAULT);
    return $data;
  }
  public function insertUser($data)
  {
    $this->save($data);
    return $this->insertID();
  }
  public function getUserByEmail($user_email)
  {
    return $this->where('user_email', $user_email)->get()->getRow();
  }
  public function getUserByUsername($user_username)
  {
    return $this->where('user_username', $user_username)->get()->getRow();
  }
  public function authenticate($user_username, $user_password)
  {
    $auth = $this->where('user_username', $user_username)->first();
    if ($auth) {
      if (password_verify($user_password, $auth['user_password'])) {
        return $auth;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function cek_status($status)
  {
    return $status == 1;
  }
}