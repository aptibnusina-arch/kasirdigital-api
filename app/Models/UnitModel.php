<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
	protected $table                = 'tkemasan';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'kodebarang',
		'kemasan',
		'hargabeli',
		'hargagrosir',
		'hargagrosir2',
		'hargajual',
		'isi',
		'konversi',
		'satuandasar',
		'produk',
		'id_apotek',
		'member',
	];

}