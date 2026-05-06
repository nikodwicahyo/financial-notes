<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\CategoryModel;

class Transactions extends BaseController
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
        $filters = [
            'type' => $this->request->getGet('type'),
            'category_id' => $this->request->getGet('category_id'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to')
        ];

        $pager = \Config\Services::pager();
        $transactions = $this->transactionModel->getFiltered($filters)->paginate(10);

        $data = [
            'title' => 'Transaksi',
            'transactions' => $transactions,
            'categories' => $this->categoryModel->findAll(),
            'filters' => $filters,
            'pager' => $this->transactionModel->pager
        ];
        
        return view('transactions/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Transaksi',
            'categories' => $this->categoryModel->findAll()
        ];
        
        return view('transactions/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|max_length[150]',
            'type' => 'required|in_list[income,expense]',
            'amount' => 'required|numeric|greater_than[0]',
            'category_id' => 'required|is_natural_no_zero',
            'transaction_date' => 'required|valid_date',
            'note' => 'permit_empty'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'type' => $this->request->getPost('type'),
            'amount' => $this->request->getPost('amount'),
            'category_id' => $this->request->getPost('category_id'),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'note' => $this->request->getPost('note')
        ];

        if ($this->transactionModel->insert($data)) {
            return redirect()->to('transactions')->with('success', 'Transaksi berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan transaksi');
        }
    }

    public function edit($id)
    {
        $transaction = $this->transactionModel->find($id);
        
        if (!$transaction) {
            return redirect()->to('transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Transaksi',
            'transaction' => $transaction,
            'categories' => $this->categoryModel->findAll()
        ];
        
        return view('transactions/edit', $data);
    }

    public function update($id)
    {
        $transaction = $this->transactionModel->find($id);
        
        if (!$transaction) {
            return redirect()->to('transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|max_length[150]',
            'type' => 'required|in_list[income,expense]',
            'amount' => 'required|numeric|greater_than[0]',
            'category_id' => 'required|is_natural_no_zero',
            'transaction_date' => 'required|valid_date',
            'note' => 'permit_empty'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'type' => $this->request->getPost('type'),
            'amount' => $this->request->getPost('amount'),
            'category_id' => $this->request->getPost('category_id'),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'note' => $this->request->getPost('note')
        ];

        if ($this->transactionModel->update($id, $data)) {
            return redirect()->to('transactions')->with('success', 'Transaksi berhasil diupdate');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate transaksi');
        }
    }

    public function delete($id)
    {
        $transaction = $this->transactionModel->find($id);
        
        if (!$transaction) {
            return redirect()->to('transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        if ($this->transactionModel->delete($id)) {
            return redirect()->to('transactions')->with('success', 'Transaksi berhasil dihapus');
        } else {
            return redirect()->to('transactions')->with('error', 'Gagal menghapus transaksi');
        }
    }
}