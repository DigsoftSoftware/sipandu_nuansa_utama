<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4>Edit Wilayah</h4>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('dashboard/wilayah/update/' . $wilayah->id) ?>" method="POST">
                <div class="mb-3">
                    <label for="wilayah" class="form-label">Nama Wilayah</label>
                    <input type="text" name="wilayah" value="<?= $wilayah->wilayah ?>" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?= base_url('dashboard/wilayah/view') ?>" class="btn btn-danger">Kembali</a>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>
