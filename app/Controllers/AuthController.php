<?php

namespace App\Controllers;

use App\Models\AuthModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $model = new AuthModel();
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }

    public function login() {
        $model = new AuthModel();

        $rules = [
			'id_usaha'     => 'required',
			'username'     => 'required',
			'password' => 'required|min_length[8]',
		];

		$errorsCustom = [
			'id_usaha' => [
				'required' => 'Id usaha wajib diisi.',
			],
			'username' => [
				'required'   => 'Username wajib diisi.',
			],
			'password' => [
				'required'   => 'Password wajib diisi.',
				'min_length' => 'Password minimal {param} karakter.',
			],
		
		];

		$validation = \Config\Services::validation();
		$validation->setRules($rules, $errorsCustom);

		if (! $validation->withRequest($this->request)->run()) {
			return $this->respond([
				'status' => 400,
				'error'  => $validation->getErrors(),
			], 400);
		}

        $id_usaha = $this->request->getVar('id_usaha');
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $userId = $model->where('apt_id', $id_usaha)
                      ->first();
        $userName = $model
                      ->where('username', $username)
                      ->first();
        if(!$userId){
            return $this->respond([
                'status' => 'error_id', 
                'errors' => 'Id usaha tidak terdaftar'], 
                401);

            } 

        if (!$userName) {
            return $this->respond([
                'status' => 'error_us', 
                'errors' => 'username tidak terdaftar'], 
                401);
            }    

        if (!password_verify($password, $userId['password'])) {
                return $this->respond([
                    'status' => 'error_ps', 
                    'errors' => 'password salah'], 
                    401);
            }               

        // if (!$user || !password_verify($password, $user['password'])) {
        //     return $this->fail(['message' => 'Invalid credentials'], 401);
        // }

        $payload = [
            "iss" => "ci4-api",
            "iat" => time(),
            "exp" => time() + 3600, // 1 jam
            "data" => [
                "id"       => $userId['id_apotek'],
                "id_usaha" => $userId['apt_id'],
                "username" => $userId['username']
            ]
        ];

        $token = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');

        return $this->respond([
            'status' => 'success',
            'token' => $token
        ]);
    }
}