<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
   protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'cart_data', 'additional_fee', 'discount', 'subtotal', 'grand_total', 'created_at'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}