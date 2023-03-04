<?php namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

Class MemberModel extends Model
{
    protected $db;
    protected $primaryKey = "idx";
    protected $table = "member_info";
    protected $allowedFields = [
        'name',
        'nickname',
        'password',
        'hp_no',
        'email',
        'gender',
        'create_datetime',
        'ip_address'
    ];

    public function member_join($insert_array)
    {
        $result = $this->insert($insert_array, false);
        return $result;
    }

    public function member_search($page, $list_row, $search_txt)
    {    
        $sql = "SELECT  idx, name, nickname, hp_no, email, gender,
                        (SELECT order_no 
                        FROM order_info
                        WHERE member_idx = a.idx 
                        ORDER BY order_datetime DESC 
                        LIMIT 1) AS last_order_no
                FROM member_info AS a ";
        if(!empty($search_txt)) {
            $sql .= " WHERE (name LIKE '%".$this->escapeLikeString($search_txt)."%' ESCAPE '!' || email LIKE '%".$this->escapeLikeString($search_txt)."%' ESCAPE '!') ";
        }
        $sql .= "LIMIT ".($page*$list_row).", ".$list_row;

        $query = $this->query($sql);
        return $query->getResultArray();
    }
}