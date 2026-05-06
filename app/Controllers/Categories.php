<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kategori',
            'categories' => $this->categoryModel->findAll()
        ];
        
        return view('categories/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori'
        ];
        
        return view('categories/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[100]',
            'icon' => 'permit_empty|max_length[50]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'icon' => $this->request->getPost('icon') ?: 'bi-tag'
        ];

        if ($this->categoryModel->insert($data)) {
            return redirect()->to('categories')->with('success', 'Kategori berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori');
        }
    }

    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('categories')->with('error', 'Kategori tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Kategori',
            'category' => $category
        ];
        
        return view('categories/edit', $data);
    }

    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('categories')->with('error', 'Kategori tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[100]',
            'icon' => 'permit_empty|max_length[50]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'icon' => $this->request->getPost('icon') ?: 'bi-tag'
        ];

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('categories')->with('success', 'Kategori berhasil diupdate');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate kategori');
        }
    }

    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('categories')->with('error', 'Kategori tidak ditemukan');
        }

        if ($this->categoryModel->isUsedByTransaction($id)) {
            return redirect()->to('categories')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh transaksi');
        }

        if ($this->categoryModel->delete($id)) {
            return redirect()->to('categories')->with('success', 'Kategori berhasil dihapus');
        } else {
            return redirect()->to('categories')->with('error', 'Gagal menghapus kategori');
        }
    }
}