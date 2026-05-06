<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class Dashboard extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $totalIncome = $this->transactionModel->getTotalIncome();
        $totalExpense = $this->transactionModel->getTotalExpense();
        $balance = $this->transactionModel->getBalance();
        
        $recentTransactions = $this->transactionModel->getRecent(5);
        $monthlyData = $this->transactionModel->getMonthlyData(date('Y'));
        $expenseByCategory = $this->transactionModel->getExpenseByCategory(date('m'), date('Y'));

        $data = [
            'title' => 'Dashboard',
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'recentTransactions' => $recentTransactions,
            'monthlyData' => $monthlyData,
            'expenseByCategory' => $expenseByCategory
        ];
        
        return view('dashboard/index', $data);
    }
}