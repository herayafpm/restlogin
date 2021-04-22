<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Firebase\JWT\JWT;
use App\Models\UsersModel;

class AuthApiFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    $agent = $request->getUserAgent();

    if ($agent->isBrowser()) {
      $currentAgent = $agent->getBrowser() . ' ' . $agent->getVersion();
    } elseif ($agent->isRobot()) {
      $currentAgent = $agent->robot();
    } elseif ($agent->isMobile()) {
      $currentAgent = $agent->getMobile();
    } else {
      $currentAgent = 'Unidentified User Agent';
    }

    echo $currentAgent;
    echo "\n";

    echo $agent->getPlatform();
    die();
    $response = service('response');
    if (!$request->getHeader('Authorization')) {
      $response->setStatusCode(401);
      $response->setBody(json_encode(["status" => 0, "message" => "Unauthorized", "data" => []]));
      $response->setHeader('Content-type', 'application/json');
      return $response;
    }
    try {

      $jwt = explode("Bearer ", $request->getHeader('Authorization')->getValue())[1];
      $decoded = JWT::decode($jwt, file_get_contents(APPPATH . '../key/public.pem'), array('RS256'));
      $userModel = new UsersModel();
      $user = $userModel->getUserByUsername($decoded->user_username);
      if (!$userModel->cek_status($user->user_aktif)) {
        throw new \Exception("Akun anda belum aktif, silahkan kontak distributor untuk konfirmasi");
      }
      $request->user = $user;
    } catch (\Exception $th) {
      $response->setStatusCode(401);
      $response->setBody(json_encode(["status" => 0, "message" => $th->getMessage(), "data" => []]));
      $response->setHeader('Content-type', 'application/json');
      return $response;
    }
  }

  //--------------------------------------------------------------------

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do something here
  }
}
