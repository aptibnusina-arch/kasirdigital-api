<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductModel;
use App\Models\UnitModel;

class UnitController extends ResourceController
{

	use ResponseTrait;
    protected $modelName =  UnitModel::class;
    protected $format    = 'json';
	
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	public function index()
	{

		$id_apotek = $this->request->getVar('id') ?? 24;

		$kode = $this->request->getPost('kodeobat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($kode !== null) {
            $data = $this->model
			->join('tsatuanbarang', 'tsatuanbarang.id = tkemasan.kemasan')
			->where('kodebarang', $kode)->findAll();
        } else {
            $data = $this->model->join('tsatuanbarang', 'tsatuanbarang.id = tkemasan.kemasan')->findAll();
        }

        return $this->respond($data);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($kodeobat = null) {
		$id_apotek = $this->request->getVar('id') ?? 24;

		if (!$kodeobat) {
		return $this->failNotFound('Kode obat tidak diberikan');
		}

		$stokModel = new ProductModel();
		$row = $stokModel->select('MAX(hargabelippn) AS hargabeli')->where('kodeobat', $kodeobat)
						->where('id_apotek', $id_apotek)->first();
		$hargabeli = $row['hargabeli'] ?? 0;
	
		// hargaeceran 
		$listEceran = $this->model
				->select('hargajual')
				->where('kodebarang', $kodeobat)
				->where('id_apotek', $id_apotek)
				->first();

		$kEceran = 1 + ($listEceran['hargajual']/100);

		$list = $this->model
				->select("tkemasan.id, tkemasan.kodebarang, hargajual, isi, SUM({$kEceran} * tkemasan.isi * {$hargabeli}) AS harga, {$hargabeli}, tsatuanbarang.satuanbarang", false)
				->join('tsatuanbarang', 'tsatuanbarang.id = tkemasan.kemasan')
				->where('kodebarang', $kodeobat)
				->where('id_apotek', $id_apotek)
				->groupBy('tkemasan.id')
				->findAll();

		// harga grosir1


		// harga grosir2

		
		return $this->respond([
			'satuan_jual' => $list,
			'hargabeli' => $hargabeli
		]);
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		//
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
	{
		//
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		//
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
		//
	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
		//
	}
}