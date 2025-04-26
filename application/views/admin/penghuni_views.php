<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Menunggu Verifikasi</h4>
                <a href="<?= base_url('dashboard/penghuni/create_admin') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Penghuni
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="myTable1">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($penghuni_diproses as $p): ?>
                        <tr>
                            <td><?= $p->nama_lengkap ?></td>
                            <td><?= $p->nik ?></td>
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
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="myTable2">
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
                                <td>
                                    <a href="<?= base_url('dashboard/penghuni/detail/'.$p->id) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
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
</div>

<?php $this->load->view('partials/footer'); ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            $.ajax({
                url: '<?= base_url('dashboard/penghuni/delete/') ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data penghuni berhasil dihapus',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = '<?= base_url('dashboard/penghuni/view') ?>';
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    window.location.href = '<?= base_url('dashboard/penghuni/view') ?>';
                }
            });
        }
    });
}

function tolak(id) {
    Swal.fire({
        title: 'Tolak Penghuni?',
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Tulis alasan penolakan...',
        showCancelButton: true,
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan harus diisi!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('dashboard/penghuni/verifikasi/') ?>' + id + '/Ditolak',
                type: 'POST',
                data: { alasan: result.value },
                dataType: 'json',
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data penghuni berhasil ditolak',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = '<?= base_url('dashboard/penghuni/view') ?>';
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menolak data',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

$(document).ready(function() {
    $('#myTable1').DataTable({
        "order": [[0, "asc"]],
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
    });

    $('#myTable2').DataTable({
        "order": [[0, "asc"]],
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
    });

    <?php if($this->session->flashdata('success')): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?= $this->session->flashdata('success') ?>',
            icon: 'success',
            position: 'center',
            showConfirmButton: false,
            timer: 3000
        });
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?= $this->session->flashdata('error') ?>',
            icon: 'error',
            position: 'center',
            showConfirmButton: false,
            timer: 3000
        });
    <?php endif; ?>

    $('form[action*="verifikasi"]').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        
        Swal.fire({
            title: 'Terima Penghuni?',
            text: "Anda yakin ingin menerima penghuni ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, terima!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data penghuni berhasil diterima',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = '<?= base_url('dashboard/penghuni/view') ?>';
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menerima data',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
});
</script>