<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
  protected $table = 'tbl_daftar_member';
  protected $primaryKey = 'id_member';
  protected $allowedFields = ['nama','email','telepon','token','password'];


  	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// protected $validationRules = [
    //     'email'    => 'required|valid_email|is_unique[users.email]',
    //     'nama'     => 'required',
    //     'telepon'  => 'required',
    //     'password' => 'required|min_length[6]',
    // ];

    // protected $validationMessages = [
    //     'email' => [
    //         'is_unique'  => 'Email sudah terdaftar.',
    //         'required'   => 'Email wajib diisi.',
    //         'valid_email'=> 'Format email tidak valid.',
    //     ],
    //     'nama'  => [
    //         'required' => 'Nama wajib diisi.',
    //     ],
	// 	'telepon'  => [
    //         'required' => 'Telepon wajib diisi.',
    //     ],
    //     'password' => [
    //         'required'   => 'Password wajib diisi.',
    //         'min_length' => 'Password minimal 6 karakter.',
    //     ],
    // ];
	
}