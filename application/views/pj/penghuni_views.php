<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title mb-0">Data Penghuni</h4>
        <a href="<?= base_url('dashboard/penghuni/create') ?>" class="btn btn-primary">Tambah Penghuni</a>
    </div>

    <table class="table table-striped" id="myTable">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Alamat Sekarang</th>
                <th>Tanggal Masuk</th>
                <th>Status Verifikasi</th>
                <th>Alasan Penolakan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($penghuni as $p): ?>
                <tr>
                    <td><?= $p->nama_lengkap ?></td>
                    <td><?= $p->nik ?></td>
                    <td><?= $p->alamat_sekarang ?></td>
                    <td><?= date('d/m/Y', strtotime($p->tanggal_masuk)) ?></td>
                    <td>
                        <?php if ($p->status_verifikasi == 'Diproses'): ?>
                            <span class="badge bg-warning">Diproses</span>
                        <?php elseif ($p->status_verifikasi == 'Diterima'): ?>
                            <span class="badge bg-success">Diterima</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $p->alasan ?? '-' ?></td>
                    <td>
                        <a href="<?= base_url('dashboard/penghuni/detail/'.$p->id) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>