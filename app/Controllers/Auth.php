<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{

	protected $format = 'json';

	public function create()
	{
		/**
		 * JWT claim typess
		 * https://auth0.com/docs/tokens/concepts/jwt-claims#reserved-claims
		 */

		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');

		// add code to fetch through db and check they are valid
		// sending no email and password also works here because both are empty
		if ($email === $password) {
            $time = time();
            $key = Services::getSecretKey();
			$payload = [
                'iat' => $time, //tempo de inicialização do token
                'exp' => $time + 60, //tempo de duração do tolen
                //'data' => ['email'=>]
			];

			/**
			 * IMPORTANT:
			 * You must specify supported algorithms for your application. See
			 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
			 * for a list of spec-compliant algorithms.
			 */
			$jwt = JWT::encode($payload, $key);
			return $this->respond(['token' => $jwt], 200);
		}

		return $this->respond(['message' => 'Invalid login details'], 401);
    }
    
    protected function validateToken($token){
        try{
            $key = Services::getSecretKey();
        }
        catch(\Exception $e){
            return false;
        }
    }
}