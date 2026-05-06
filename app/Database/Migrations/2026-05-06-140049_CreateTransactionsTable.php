<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'auto_increment' => true],
            'title'            => ['type' => 'VARCHAR', 'constraint' => 150],
            'type'             => ['type' => 'ENUM', 'constraint' => ['income','expense']],
            'amount'           => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'category_id'      => ['type' => 'INT'],
            'transaction_date' => ['type' => 'DATE'],
            'note'             => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
