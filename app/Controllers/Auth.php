<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{

	protected $format = 'json';

	public function create()
	{
		//Elementos que passarão pela verificação
		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');

		//realizar consultas com o banco para verificações mais reais
		
		//caso atenda a verificação será gerado um token
		if ($email === $password) {
            $time = time();
            $key = Services::getSecretKey();
			$payload = [
                'iat' => $time, //tempo de inicialização do token
                'exp' => $time + 60*30, //tempo de duração do tolen
                'data' => ['pass1'=>$email, 'pass2'=>$password]
			];

	
			$jwt = JWT::encode($payload, $key);
			return $this->respond(['token' => $jwt], 200);
		}

		return $this->respond(['message' => 'Login inválido'], 401);
    }
    
    public function validateToken($token){
        try{
			//retorna o token descriptado
			$key = Services::getSecretKey();
			return JWT::decode($token, $key, array('HS256'));
        }
        catch(\Exception $e){
            return false;
        }
	}
	
	public function verifyToken(){
		$key = Services::getSecretKey();
		$token = $this->request->getPost("token");

		if(!$this->validateToken($token)){
			//caso o token não seja valido retorna erro
			return $this->respond(['message'=>'Token inválido', 401]);
		}
		else{
			$data = JWT::decode($token, $key, array('HS256'));
			return $this->respond(['data'=>$data, 200]);
		}
	}
}