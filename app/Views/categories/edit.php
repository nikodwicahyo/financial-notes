<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Kategori<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Edit Kategori</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('categories/update/' . $category['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" value="<?= old('name', $category['name']) ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (Bootstrap Icons)</label>
                        <input type="text" class="form-control <?= isset($errors['icon']) ? 'is-invalid' : '' ?>" 
                               id="icon" name="icon" value="<?= old('icon', $category['icon']) ?>" placeholder="bi-tag">
                        <div class="form-text">Contoh: bi-cup-hot, bi-car-front, bi-bag</div>
                        <?php if (isset($errors['icon'])): ?>
                            <div class="invalid-feedback"><?= $errors['icon'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="<?= base_url('categories') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>