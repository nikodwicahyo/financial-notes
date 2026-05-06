<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'icon'];
    protected $useTimestamps = false;

    public function isUsedByTransaction($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transactions');
        $count = $builder->where('category_id', $id)->countAllResults();
        return $count > 0;
    }
}
