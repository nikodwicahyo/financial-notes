<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card <?= $balance >= 0 ? 'bg-success' : 'bg-danger' ?> text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Saldo</h6>
                        <h4>Rp <?= number_format($balance, 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-wallet2 fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Pemasukan</h6>
                        <h4>Rp <?= number_format($totalIncome, 0, ',', '.') ?></h4>
                        <small>Bulan ini</small>
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
                        <h4>Rp <?= number_format($totalExpense, 0, ',', '.') ?></h4>
                        <small>Bulan ini</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-arrow-down-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Pemasukan vs Pengeluaran (12 Bulan Terakhir)</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Pengeluaran per Kategori</h5>
            </div>
            <div class="card-body">
                <?php if (empty($expenseByCategory)): ?>
                    <p class="text-center text-muted">Belum ada data pengeluaran bulan ini</p>
                <?php else: ?>
                    <div style="height: 300px;">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Transaksi Terbaru</h5>
                <a href="<?= base_url('transactions') ?>" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($recentTransactions)): ?>
                    <p class="text-center text-muted">Belum ada transaksi</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTransactions as $transaction): ?>
                                    <tr>
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
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Debug: Check if data is available
console.log('Monthly Data:', <?= json_encode($monthlyData) ?>);
console.log('Expense by Category:', <?= json_encode($expenseByCategory) ?>);

// Prepare data for charts
const monthlyData = <?= json_encode($monthlyData) ?>;
const expenseByCategory = <?= json_encode($expenseByCategory) ?>;

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Bar Chart
    const barCanvas = document.getElementById('barChart');
    if (barCanvas && monthlyData) {
        const barCtx = barCanvas.getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pemasukan',
                    data: monthlyData.map(item => parseFloat(item.income) || 0),
                    backgroundColor: '#198754',
                    borderColor: '#198754',
                    borderWidth: 1
                }, {
                    label: 'Pengeluaran',
                    data: monthlyData.map(item => parseFloat(item.expense) || 0),
                    backgroundColor: '#dc3545',
                    borderColor: '#dc3545',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Doughnut Chart
    const doughnutCanvas = document.getElementById('doughnutChart');
    if (doughnutCanvas && expenseByCategory && expenseByCategory.length > 0) {
        const doughnutCtx = doughnutCanvas.getContext('2d');
        const doughnutChart = new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: expenseByCategory.map(item => item.name),
                datasets: [{
                    data: expenseByCategory.map(item => parseFloat(item.total) || 0),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#C9CBCF'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>