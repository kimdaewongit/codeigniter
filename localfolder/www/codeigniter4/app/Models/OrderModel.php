<?php namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

Class OrderModel extends Model
{
    protected $db;
    protected $primaryKey = "idx";
    protected $table = "order_info";
    protected $allowedFields = [
        'order_no',
        'member_idx',
        'product_idx',
        'product_name',
        'order_status',
        'order_datetime',
        'ip_address'
    ];

    public function order_list_search($where_array)
    {
        $query = $this->where($where_array)->findAll();
        return $query;
    }
}