<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title','type','amount','category_id','transaction_date','note'];
    protected $useTimestamps = true;

    public function getTotalIncome()
    {
        return $this->where('type', 'income')->selectSum('amount')->first()['amount'] ?? 0;
    }

    public function getTotalExpense()
    {
        return $this->where('type', 'expense')->selectSum('amount')->first()['amount'] ?? 0;
    }

    public function getBalance()
    {
        return $this->getTotalIncome() - $this->getTotalExpense();
    }

    public function getMonthlyData($year)
    {
        $db = \Config\Database::connect();
        $data = [];
        
        for ($month = 1; $month <= 12; $month++) {
            // Get income for this month
            $incomeQuery = $db->query("SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE type = 'income' AND YEAR(transaction_date) = ? AND MONTH(transaction_date) = ?", [$year, $month]);
            $income = $incomeQuery->getRow()->total;
            
            // Get expense for this month
            $expenseQuery = $db->query("SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE type = 'expense' AND YEAR(transaction_date) = ? AND MONTH(transaction_date) = ?", [$year, $month]);
            $expense = $expenseQuery->getRow()->total;
            
            $data[] = [
                'month' => $month,
                'income' => (float)$income,
                'expense' => (float)$expense
            ];
        }
        
        return $data;
    }

    public function getExpenseByCategory($month, $year)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT c.name, COALESCE(SUM(t.amount), 0) as total 
            FROM transactions t 
            JOIN categories c ON c.id = t.category_id 
            WHERE t.type = 'expense' 
            AND MONTH(t.transaction_date) = ? 
            AND YEAR(t.transaction_date) = ? 
            GROUP BY t.category_id, c.name
            HAVING total > 0
        ", [$month, $year]);
        
        return $query->getResultArray();
    }

    public function getRecent($limit = 5)
    {
        return $this->select('transactions.*, categories.name as category_name')
                   ->join('categories', 'categories.id = transactions.category_id')
                   ->orderBy('transactions.transaction_date', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    public function getFiltered($filters)
    {
        $builder = $this->select('transactions.*, categories.name as category_name')
                       ->join('categories', 'categories.id = transactions.category_id');

        if (!empty($filters['type'])) {
            $builder->where('transactions.type', $filters['type']);
        }

        if (!empty($filters['category_id'])) {
            $builder->where('transactions.category_id', $filters['category_id']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('transactions.transaction_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('transactions.transaction_date <=', $filters['date_to']);
        }

        return $builder->orderBy('transactions.transaction_date', 'DESC');
    }
}