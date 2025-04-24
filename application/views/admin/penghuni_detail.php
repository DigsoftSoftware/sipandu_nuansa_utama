<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card mt-4">
        <div class="card-body">
            <h4>Detail Data Penghuni</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Lengkap</th>
                    <td><?= $penghuni->nama_lengkap ?></td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td><?= $penghuni->nik ?></td>
                </tr>
                <tr>
                    <th>Tempat, Tanggal Lahir</th>
                    <td><?= $penghuni->tempat_lahir ?>, <?= $penghuni->tanggal_lahir ?></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td><?= $penghuni->jenis_kelamin ?></td>
                </tr>
                <tr>
                    <th>Agama</th>
                    <td><?= $penghuni->agama ?></td>
                </tr>
                <tr>
                    <th>Alamat Sekarang</th>
                    <td><?= $penghuni->alamat_sekarang ?></td>
                </tr>
                <tr>
                    <th>Status Verifikasi</th>
                    <td><span class="badge bg-<?= $penghuni->status_verifikasi == 'Diterima' ? 'success' : ($penghuni->status_verifikasi == 'Ditolak' ? 'danger' : 'warning') ?>">
                        <?= $penghuni->status_verifikasi ?>
                    </span></td>
                </tr>
                <tr>
                    <th>Alasan Penolakan</th>
                    <td><?= $penghuni->alasan_penolakan ?: '-' ?></td>
                </tr>
            </table>
            <a href="<?= base_url('dashboard/penghuni') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>
