<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Tambah Transaksi<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Tambah Transaksi Baru</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('transactions/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Transaksi</label>
                                <input type="text" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                                       id="title" name="title" value="<?= old('title') ?>" required>
                                <?php if (isset($errors['title'])): ?>
                                    <div class="invalid-feedback"><?= $errors['title'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Jenis</label>
                                <select class="form-select <?= isset($errors['type']) ? 'is-invalid' : '' ?>" 
                                        id="type" name="type" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="income" <?= old('type') == 'income' ? 'selected' : '' ?>>Pemasukan</option>
                                    <option value="expense" <?= old('type') == 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
                                </select>
                                <?php if (isset($errors['type'])): ?>
                                    <div class="invalid-feedback"><?= $errors['type'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Jumlah</label>
                                <input type="number" class="form-control <?= isset($errors['amount']) ? 'is-invalid' : '' ?>" 
                                       id="amount" name="amount" value="<?= old('amount') ?>" min="1" required>
                                <?php if (isset($errors['amount'])): ?>
                                    <div class="invalid-feedback"><?= $errors['amount'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select <?= isset($errors['category_id']) ? 'is-invalid' : '' ?>" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                            <?= esc($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['category_id'])): ?>
                                    <div class="invalid-feedback"><?= $errors['category_id'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control <?= isset($errors['transaction_date']) ? 'is-invalid' : '' ?>" 
                               id="transaction_date" name="transaction_date" value="<?= old('transaction_date', date('Y-m-d')) ?>" required>
                        <?php if (isset($errors['transaction_date'])): ?>
                            <div class="invalid-feedback"><?= $errors['transaction_date'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control <?= isset($errors['note']) ? 'is-invalid' : '' ?>" 
                                  id="note" name="note" rows="3"><?= old('note') ?></textarea>
                        <?php if (isset($errors['note'])): ?>
                            <div class="invalid-feedback"><?= $errors['note'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('transactions') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>