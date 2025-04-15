<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('admin/sidebar_admin'); ?>
<?php $this->load->view('admin/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Kepala Lingkungan</h4>

                <form action="<?= base_url('dashboard/kaling/update/'.$kaling->id) ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" value="<?= $kaling->username ?>" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" value="<?= $kaling->nama ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wilayah</label>
                        <select class="form-select" name="wilayah_id" required>
                            <option value="">Pilih Wilayah</option>
                            <?php foreach ($wilayah as $w): ?>
                                <option value="<?= $w->id ?>" <?= ($kaling->wilayah_id == $w->id) ? 'selected' : '' ?>>
                                    <?= $w->wilayah ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('dashboard/kaling/view') ?>" class="btn btn-secondary">Kembali</a>
                </form>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>