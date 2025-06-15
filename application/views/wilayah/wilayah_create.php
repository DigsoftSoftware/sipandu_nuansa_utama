<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4>Tambah Wilayah</h4>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('dashboard/wilayah/store') ?>" method="POST">
                <div class="mb-3">
                    <label for="wilayah" class="form-label">Nama Wilayah</label>
                    <input type="text" name="wilayah" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/wilayah/view') ?>'">Kembali</button>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>