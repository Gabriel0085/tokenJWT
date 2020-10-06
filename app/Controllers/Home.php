<?php namespace App\Controllers;

class Home extends Auth //Utiliza os metodos de Auth
{
	public function index()
	{
		return view('welcome_message');
	}
	
	public function insertUser(){
		$token = $this->request->getHeader('Authorization')->getValue();
		
		if($this->validateToken($token) == true){
			$data = [
				"name" => $this->request->getPost('name'),
				"email" => $this->request->getPost('email'),
				"cpf" => $this->request->getPost('cpf'),
			];
			//$this->model->inserGlobal($data);
			return $this->respond(['data'=>'Inserido com sucesso', 200]);
		}
		else{
			return $this->respond(['message'=>'token invalido, impossível inserção', 401]);
		}
	}
	//--------------------------------------------------------------------

}
