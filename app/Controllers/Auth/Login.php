<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use \Firebase\JWT\JWT;

class Login extends ResourceController
{

    protected $format       = 'json';
    protected $modelName    = 'App\Models\UsersModel';

    public function index()
    {
        $validation =  \Config\Services::validation();
        $rules = [
            'user_username' => [
                'label'  => 'Username',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'user_password' => [
                'label'  => 'Password',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
        ];
        $dataJson = $this->request->getJson();
        $data = [
            'user_username' => htmlspecialchars($dataJson->user_username ?? ''),
            'user_password' => htmlspecialchars($dataJson->user_password ?? ''),
        ];
        $validation->setRules($rules);
        if (!$validation->run($data)) {
            return $this->respond(["status" => 0, "message" => "Validasi gagal", "data" => $validation->getErrors()], 400);
        }
        $user = $this->model->authenticate($data['user_username'], $data['user_password']);
        if ($user) {
            if ($this->model->cek_status($user['user_aktif'])) {
                $jwt = JWT::encode($user, file_get_contents(APPPATH.'../key/private.pem'),'RS256');
                $user['token'] = $jwt;
                return $this->respond(["status" => 1, "message" => "login berhasil", "data" => $user], 200);
            } else {
                return $this->respond(["status" => 0, "message" => "akun anda belum aktif, silahkan kontak admin distributor", "data" => []], 500);
            }
        } else {
            return $this->respond(["status" => 0, "message" => "username atau password salah", "data" => []], 400);
        }
    }
}
