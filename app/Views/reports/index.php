<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Laporan Keuangan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h4><?= $reportTitle ?></h4>
</div>

<!-- Filter Panel -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form method="get" action="<?= base_url('reports') ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        <?php foreach ($monthNames as $num => $name): ?>
                            <option value="<?= $num ?>" <?= $filters['month'] == $num ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" class="form-control" 
                           value="<?= $filters['year'] ?>" min="2020" max="2030">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis</label>
                    <select name="type" class="form-select">
                        <option value="">Semua</option>
                        <option value="income" <?= $filters['type'] == 'income' ? 'selected' : '' ?>>Pemasukan</option>
                        <option value="expense" <?= $filters['type'] == 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">Semua</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $filters['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Pemasukan</h6>
                        <h4>Rp <?= number_format($totals['income'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-arrow-up-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Pengeluaran</h6>
                        <h4>Rp <?= number_format($totals['expense'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-arrow-down-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card <?= $totals['balance'] >= 0 ? 'bg-success' : 'bg-warning' ?> text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Selisih (Saldo Periode)</h6>
                        <h4>Rp <?= number_format($totals['balance'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calculator fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header">
        <h5>Detail Transaksi</h5>
    </div>
    <div class="card-body">
        <?php if (empty($transactions)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <h5 class="text-muted mt-3">Tidak ada transaksi pada periode ini</h5>
                <p class="text-muted">Silakan ubah filter atau tambah transaksi baru</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Judul</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $index => $transaction): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= date('d/m/Y', strtotime($transaction['transaction_date'])) ?></td>
                                <td><?= esc($transaction['title']) ?></td>
                                <td><?= esc($transaction['category_name']) ?></td>
                                <td>
                                    <?php if ($transaction['type'] == 'income'): ?>
                                        <span class="badge bg-success">Pemasukan</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Pengeluaran</span>
                                    <?php endif; ?>
                                </td>
                                <td>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                                <td><?= esc($transaction['note']) ?: '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Summary Footer -->
            <div class="mt-3 pt-3 border-top">
                <div class="row text-center">
                    <div class="col-md-4">
                        <strong class="text-primary">
                            Total Pemasukan: Rp <?= number_format($totals['income'], 0, ',', '.') ?>
                        </strong>
                    </div>
                    <div class="col-md-4">
                        <strong class="text-danger">
                            Total Pengeluaran: Rp <?= number_format($totals['expense'], 0, ',', '.') ?>
                        </strong>
                    </div>
                    <div class="col-md-4">
                        <strong class="<?= $totals['balance'] >= 0 ? 'text-success' : 'text-warning' ?>">
                            Selisih: Rp <?= number_format($totals['balance'], 0, ',', '.') ?>
                        </strong>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>