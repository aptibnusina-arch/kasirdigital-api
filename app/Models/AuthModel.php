<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table            = 'tbl_login';
    protected $primaryKey       = 'id_login';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'akses',
        'apt_id',
        'nama',
        'username',
        'avatar',
        'password',
        'email',
        'id_apotek'
    ];


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}