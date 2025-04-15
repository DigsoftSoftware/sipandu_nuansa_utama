<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('admin/sidebar_admin'); ?>
<?php $this->load->view('admin/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Tambah Kepala Lingkungan</h4>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('dashboard/kaling/store') ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wilayah</label>
                        <select class="form-select" name="wilayah_id" required>
                            <option value="">Pilih Wilayah</option>
                            <?php foreach ($wilayah as $w): ?>
                                <option value="<?= $w->id ?>" <?= (isset($kaling) && $kaling->wilayah_id == $w->id) ? 'selected' : '' ?>>
                                    <?= $w->wilayah ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">Simpan</button>
                    <a href="<?= base_url('dashboard/kaling/view') ?>" class="btn btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>