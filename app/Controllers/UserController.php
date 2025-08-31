<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class UserController extends ResourceController
{
	use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        //
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
        $model = new \App\Models\UserModel();

        $rules = [
            'email'    => 'required|valid_email|is_unique[tbl_daftar_member.email]',
            'nama'     => 'required',
            'telepon'  => 'required|min_length[10]',
            'password' => 'required|min_length[8]',
            'konfirmasiPassword' => 'required|matches[password]',
        ];

        $errorsCustom = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Masukkan email yang valid.',
                'is_unique'   => 'Email sudah terdaftar.',
            ],
            'nama' => [
                'required' => 'Nama wajib diisi.',
            ],
            'telepon' => [
                'required'   => 'Telepon wajib diisi.',
                'min_length' => 'Telepon minimal {param} karakter.',
            ],
            'password' => [
                'required'   => 'Kata sandi wajib diisi.',
                'min_length' => 'Kata sandi minimal {param} karakter.',
            ],
            'konfirmasiPassword' => [
                'required' => 'Konfirmasi kata sandi wajib diisi.',
                'matches'  => 'Konfirmasi kata sandi tidak cocok.',
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

        $data = $this->request->getJSON(true);
        $insertData = [
            'email'    => $data['email'],
            'nama'     => $data['nama'],
            'telepon'  => $data['telepon'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ];

        if (! $model->insert($insertData)) {
            return $this->respond([
                'status' => 400,
                'error'  => $model->errors(),
            ], 400);
        }

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Registrasi berhasil.',
            'data'    => ['id' => $model->getInsertID()],
        ]);
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
}