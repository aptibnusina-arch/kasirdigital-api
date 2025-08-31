<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
	protected $table                = 'tstok';
	protected $primaryKey           = 'id_produk';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'tgl_transaksi',
		'kodeobat',
		'namaobat',
		'no_batch',
		'kadaluarsa',
		'hargabeli',
		'hargabelippn',
		'gambar',
		'stokawal',
		'stok',
		'satuan',
		'ketopname',
		'pemasok',
		'id_apotek',
		'member'
	];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

  // Get items with pagination
    public function getItems($id, $page = 1, $perPage = 20, $search = null)
    {
		 if ($search) {
            $this
			->like('namaobat', $search)
			->where('id_apotek', $id)
			->orderBy('namaobat', 'DESC'); // Search for items where the name contains the search term
        }

        return $this
		->where('id_apotek', $id)
		->orderBy('namaobat', 'DESC')
		->paginate($perPage, 'tstok', $page);
    }

    // Get total number of items (for pagination metadata)
    public function getTotalItems($id, $search)
    {
		 if ($search) {
            $this->like('namaobat', $search)
			->where('id_apotek', $id)
			->orderBy('namaobat', 'DESC'); // Search for items where the name contains the search term
        }

        return $this
		->where('id_apotek', $id)
		->countAllResults();
    }

	 public function getProducts($query = '', $start = 0, $limit = 20)
    {
        $builder = $this->builder();
        
        // Filter berdasarkan query pencarian jika ada
        if (!empty($query)) {
            $builder->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, SUM(stok) as stok, tsatuanbarang.satuanbarang')
                    ->join('tsatuanbarang', 'tsatuanbarang.ID = tstok.satuan')
                    ->like('namaobat', $query)
                    ->where('id_apotek', 24)
                    ->groupBy('kodeobat')
                    ->orderBy('namaobat','ASC');
        }

        // Paginasi
        $builder->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, SUM(stok) as stok, tsatuanbarang.satuanbarang')
                    ->join('tsatuanbarang', 'tsatuanbarang.ID = tstok.satuan')
                    ->where('id_apotek', 24)
                    ->groupBy('kodeobat')
                    ->orderBy('namaobat','ASC')
					->limit($limit, $start);

        // Mengambil hasil sebagai array
        return $builder->where('id_apotek', 24)->get()->getResultArray();
    }
	public function get_migrasi($id, $member)
	{
		$this->builder()->select('*')
			->where('id_apotek', $id) //id apotek
			->where('member', $member) //id member
			->groupBy('kodeobat');

		return $this; // This will allow the call chain to be used.
	}

	public function get_migrasi_peritem($id, $member, $kodeobat)
	{
		$this->builder()->select('*')
			->where('id_apotek', $id) //id apotek
			->where('member', $member) //id member
			->where('kodeobat', $kodeobat)
			->groupBy('kodeobat');

		return $this; // This will allow the call chain to be used.
	}

	public function jmlproduk($id)
	{
		$this->builder()->select('COUNT(DISTINCT kodeobat) as jmlproduk')
			->where('id_apotek', $id);

		return $this;
	}

	
    public function kodecabang_opname($id_apotek, $status = NULL)
    {
        if ($status == 0) {
            $this->builder()->select('tstok.kodeobat, tstok.namaobat, SUM(tstok.stok) as stok, tstok.pemasok, tstok.updated_at as terakhir_opname, tsatuanbarang.satuanbarang')
                ->join('tsatuanbarang', 'tsatuanbarang.ID=tstok.satuan', 'left')
                ->where('tstok.id_apotek', $id_apotek)
                ->groupBy('tstok.kodeobat')
				->orderBy('namaobat');
        } else {
            $this->builder()->select('tstok.kodeobat, tstok.namaobat, SUM(tstok.stok) as stok, tstok.pemasok, tstok.updated_at as terakhir_opname, tsatuanbarang.satuanbarang')
                ->join('tsatuanbarang', 'tsatuanbarang.ID=tstok.satuan', 'left')
                ->where('tstok.ketopname', $status)
                ->where('tstok.id_apotek', $id_apotek)
                ->groupBy('tstok.kodeobat')
				->orderBy('namaobat');
        }


        return $this;
        // This will allow the call chain to be used.
    }

    public function search_opname($cari = NULL, $id_apotek, $status = NULL)
    {

        if ($status == 0) {
            $this->builder()->select('tstok.kodeobat, tstok.namaobat, SUM(tstok.stok) as stok, tstok.pemasok, tstok.updated_at as terakhir_opname, tsatuanbarang.satuanbarang')
			->join('tsatuanbarang', 'tsatuanbarang.ID=tstok.satuan', 'left')
			->like('tstok.kodeobat', $cari)
			->orlike('tstok.namaobat', $cari)
			->where('tstok.id_apotek', $id_apotek)
                ->groupBy('tstok.kodeobat')
				->orderBy('namaobat');
        } else {
            $this->builder()->select('tstok.kodeobat, tstok.namaobat, SUM(tstok.stok) as stok, tstok.pemasok, tstok.updated_at as terakhir_opname, tsatuanbarang.satuanbarang')
                ->join('tsatuanbarang', 'tsatuanbarang.ID=tstok.satuan', 'left')
                ->where('tstok.ketopname', $status)
                ->where('tstok.id_apotek', $id_apotek)
                ->like('tstok.kodeobat', $cari)
                ->orlike('tstok.namaobat', $cari)
                ->groupBy('tstok.kodeobat')
				->orderBy('namaobat');
        }

        return $this; // This will allow the call chain to be used.
    }

	public function del($id, $id_apotek){
		$this->builder()->where('kodeobat', $id)->where('id_apotek', $id_apotek);
		$this->builder()->delete();
	}

public function allProduct($id)
	{
		$this->builder()->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, SUM(stok) as stok, tsatuanbarang.satuanbarang')
                    ->join('tsatuanbarang', 'tsatuanbarang.ID = tstok.satuan')
                    ->where('id_apotek', $id)
                    ->groupBy('kodeobat')
                    ->orderBy('namaobat','ASC');

		return $this;
	}


}