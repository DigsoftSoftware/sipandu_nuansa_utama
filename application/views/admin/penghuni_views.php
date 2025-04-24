<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Penghuni Menunggu Verifikasi</h4>
                <a href="<?= base_url('dashboard/penghuni/create_admin') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Penghuni
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered " id="myTable1">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Alamat Sekarang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($penghuni_diproses as $p): ?>
                        <tr>
                            <td><?= $p->nama_lengkap ?></td>
                            <td><?= $p->nik ?></td>
                            <td><?= $p->alamat_sekarang ?></td>
                            <td><span class="badge bg-warning"><?= $p->status_verifikasi ?></span></td>
                            <td>
                                <a href="<?= base_url('dashboard/penghuni/detail/'.$p->id) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="<?= base_url('dashboard/penghuni/verifikasi/'.$p->id.'/Diterima') ?>" method="post" style="display:inline;">
                                    <button class="btn btn-success btn-sm" title="Terima">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <button class="btn btn-danger btn-sm" title="Tolak" onclick="tolak(<?= $p->id ?>)">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-button" title="Hapus" onclick="confirmDelete(<?= $p->id ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Data Terverifikasi</h4>
            <div class="table-responsive" id="myTable2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Status</th>
                            <th>Alasan Penolakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penghuni_terverifikasi as $p): ?>
                            <tr>
                                <td><?= $p->nama_lengkap ?></td>
                                <td><?= $p->nik ?></td>
                                <td>
                                    <?php if ($p->status_verifikasi == 'Diterima'): ?>
                                        <span class="badge bg-success">Diterima</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $p->alasan ?? '-' ?></td>
                            <?php endforeach; ?>
                                <td>
                                    <a href="<?= base_url('dashboard/penghuni/detail/'.$p->id) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" title="Print Surat" onclick="tolak(<?= $p->id ?>)">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('partials/footer'); ?>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('dashboard/penghuni/delete/') ?>' + id;
        }
    });
}

function tolak(id) {
    Swal.fire({
        title: 'Tolak Penghuni?',
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Tulis alasan...',
        showCancelButton: true,
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        preConfirm: (alasan) => {
            if (!alasan) {
                Swal.showValidationMessage('Alasan harus diisi!');
            } else {
                $.post('<?= base_url('dashboard/penghuni/verifikasi/') ?>' + id + '/Ditolak', 
                    { alasan: alasan }, 
                    function() {
                        location.reload();
                    }
                );
            }
        }
    });
}

$(document).ready(function() {
    $('#myTable1').DataTable();
});
</script>