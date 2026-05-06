<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Makan & Minum', 'icon' => 'bi-cup-hot'],
            ['name' => 'Transport',     'icon' => 'bi-car-front'],
            ['name' => 'Belanja',       'icon' => 'bi-bag'],
            ['name' => 'Kesehatan',     'icon' => 'bi-heart-pulse'],
            ['name' => 'Hiburan',       'icon' => 'bi-controller'],
            ['name' => 'Gaji',          'icon' => 'bi-cash-coin'],
            ['name' => 'Freelance',     'icon' => 'bi-laptop'],
            ['name' => 'Lainnya',       'icon' => 'bi-three-dots'],
        ];
        $this->db->table('categories')->insertBatch($data);
    }
}
