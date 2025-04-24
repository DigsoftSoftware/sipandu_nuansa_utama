<?php $this->load->view('partials/header'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Form Surat Izin Tinggal</h5>
            <?php echo form_open('surat/generate_surat_izin_tinggal'); ?>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" required>
                </div>
                <div class="mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="tujuan" class="form-label">Tujuan</label>
                    <textarea class="form-control" id="tujuan" name="tujuan" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
                    <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku" required>
                </div>
                <button type="submit" class="btn btn-primary">Generate Surat</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>