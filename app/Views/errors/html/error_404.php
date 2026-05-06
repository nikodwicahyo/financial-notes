<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 text-center">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
                        <h1 class="display-4 text-primary mt-3">404</h1>
                        <h4 class="mb-3">Halaman Tidak Ditemukan</h4>
                        <p class="text-muted mb-4">
                            Maaf, halaman yang Anda cari tidak dapat ditemukan. 
                            Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="<?= base_url('/') ?>" class="btn btn-primary">
                                <i class="bi bi-house"></i> Kembali ke Dashboard
                            </a>
                            <button onclick="history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>