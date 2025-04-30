<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                <h5 class="mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Perhatian! Panduan Penggunaan
                </h5>
                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                    <li>Klik tombol <strong>Tambah Penghuni</strong> <i class="fas fa-plus text-primary"></i> untuk menambahkan data penghuni baru.</li>
                    <li>Gunakan tombol <strong>Lihat Detail</strong> <i class="fas fa-eye text-info"></i> untuk melihat informasi lengkap penghuni.</li>
                    <li>Jika data sudah sesuai, klik <strong>Terima</strong> <i class="fas fa-check-circle text-success"></i> untuk memverifikasi penghuni.</li>
                    <li>Jika data tidak sesuai, klik <strong>Tolak</strong> <i class="fas fa-times-circle text-danger"></i> dan isikan alasan penolakan.</li>
                    <li>Gunakan tombol <strong>Hapus</strong> <i class="fas fa-trash-alt text-danger"></i> untuk menghapus data penghuni yang tidak diperlukan.</li>
                    <li>Pastikan hanya memilih data yang sudah <strong>terverifikasi Kepala Lingkungan</strong>.</li>
                    <li>Cetak dokumen menggunakan kertas <strong>F4 atau legal</strong> dengan <strong>scale dokumen 100%</strong> untuk hasil terbaik.</li>
                </ul>
            </div>
        </div>
    </div>

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
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Penanggung Jawab</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($penghuni_diproses as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $p->nama_lengkap ?></td>
                            <td><?= $p->nik ?></td>
                            <td><?= $p->pj_nama ?></td>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Data Terverifikasi</h4>
                <div class="d-flex align-items-center">
                    <select class="form-select" name="" id="">
                        <option value="">Pilih Penanggung Jawab</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Penanggung Jawab</th>
                            <th>Status</th>
                            <th>Alasan Penolakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penghuni_terverifikasi as $i => $p): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><?= $p->nama_lengkap ?></td>
                                <td><?= $p->nik ?></td>
                                <td><?= $p->pj_nama ?></td>
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

<?php $this->load->view('partials/watermark'); ?>
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
    $('#myTable1').DataTable({});
    $('#myTable2').DataTable({});

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