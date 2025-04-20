<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Data Penanggung Jawab</h4>
                <form action="<?= base_url('dashboard/pj/update/'.$pj->id) ?>" method="post">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= $pj->nama ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $pj->email ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Handphone</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= $pj->no_hp ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" required><?= $pj->alamat ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                    <a href="<?= base_url('dashboard/pj/view') ?>" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>
