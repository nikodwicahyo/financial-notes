<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\CategoryModel;

class Reports extends BaseController
{
    protected $transactionModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        // Get filter parameters with defaults
        $month = $this->request->getGet('month') ?: date('m');
        $year = $this->request->getGet('year') ?: date('Y');
        $type = $this->request->getGet('type') ?: '';
        $categoryId = $this->request->getGet('category_id') ?: '';

        // Build filters for getFiltered method
        $filters = [
            'type' => $type,
            'category_id' => $categoryId,
            'date_from' => $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01',
            'date_to' => $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-31'
        ];

        // Get filtered transactions
        $transactions = $this->transactionModel->getFiltered($filters)->findAll();

        // Calculate totals for the filtered period
        $totalIncome = 0;
        $totalExpense = 0;
        
        foreach ($transactions as $transaction) {
            if ($transaction['type'] == 'income') {
                $totalIncome += $transaction['amount'];
            } else {
                $totalExpense += $transaction['amount'];
            }
        }

        $balance = $totalIncome - $totalExpense;

        // Month names in Indonesian
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $data = [
            'title' => 'Laporan Keuangan',
            'transactions' => $transactions,
            'categories' => $this->categoryModel->findAll(),
            'filters' => [
                'month' => $month,
                'year' => $year,
                'type' => $type,
                'category_id' => $categoryId
            ],
            'totals' => [
                'income' => $totalIncome,
                'expense' => $totalExpense,
                'balance' => $balance
            ],
            'monthNames' => $monthNames,
            'reportTitle' => 'Laporan Keuangan — ' . $monthNames[(int)$month] . ' ' . $year
        ];
        
        return view('reports/index', $data);
    }
}