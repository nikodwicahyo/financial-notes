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
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $income = $this->where('type', 'income')
                          ->where('YEAR(transaction_date)', $year)
                          ->where('MONTH(transaction_date)', $month)
                          ->selectSum('amount')->first()['amount'] ?? 0;
            
            $expense = $this->where('type', 'expense')
                           ->where('YEAR(transaction_date)', $year)
                           ->where('MONTH(transaction_date)', $month)
                           ->selectSum('amount')->first()['amount'] ?? 0;
            
            $data[] = [
                'month' => $month,
                'income' => $income,
                'expense' => $expense
            ];
        }
        return $data;
    }

    public function getExpenseByCategory($month, $year)
    {
        return $this->select('categories.name, SUM(transactions.amount) as total')
                   ->join('categories', 'categories.id = transactions.category_id')
                   ->where('transactions.type', 'expense')
                   ->where('MONTH(transactions.transaction_date)', $month)
                   ->where('YEAR(transactions.transaction_date)', $year)
                   ->groupBy('transactions.category_id')
                   ->findAll();
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