<?php

namespace App\Controllers;

use App\Models\SaleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SaleController extends ResourceController
{

    protected $modelName = SaleModel::class;
    protected $format    = 'json';
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
        $data = [
            'cart_data'      => json_encode($this->request->getPost('cart')),
            'additional_fee' => (float) $this->request->getPost('additional_fee'),
            'discount'       => (float) $this->request->getPost('discount'),
            'subtotal'       => (float) $this->request->getPost('subtotal'),
            'grand_total'    => (float) $this->request->getPost('grand_total')
        ];

        if ($this->model->insert($data)) {
            return $this->respondCreated(['message' => 'Pembayaran tersimpan', 'id' => $this->model->getInsertID()]);
        }

        return $this->fail('Gagal menyimpan pembayaran', 500);
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