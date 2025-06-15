<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">

    <!-- Tabel Perhatian dan Tambah Keperluan -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">Surat Pengantar Anggota Keluarga</h5>
                <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/surat/view') ?>'">Kembali</button>
            </div>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <div class="col-md-12">
                <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-0" role="alert">
                    <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                    <ul class="mb-0 ps-3" style="list-style-type: disc;">
                        <li>Anda bisa menambahkan jenis keperluan dari surat pengantar</li>
                        <li>Anda bisa mencetak langsung Surat Pengantar di form Cetak Surat Pengantar Pendatang</li>
                        <li>Pastikan Anda memverifikasi Surat Pengantar yang di kirimkan oleh Penanggung Jawab</li>
                        <li>Gunakan Format kertas <strong>F4 atau Legal</strong> saat mencetak dokumen</li>
                        <li>Pastikan skala cetak dokumen disetel ke <strong>100%</strong> agar format tidak terpotong</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Jenis Keperluan -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">Tambah Jenis Keperluan Surat Pengantar</h5>
            </div>
            <div class="row g-2 align-items-center mb-0">
                <div class="col-md-8">
                    <input type="text" class="form-control" id="inputJenisKeperluan" placeholder="Masukkan Jenis Keperluan Baru">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100" id="btnTambahKeperluan">
                        Tambah
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-light w-100" data-bs-toggle="modal" data-bs-target="#modalListKeperluan" id="btnLihatListKeperluan">
                        List Keperluan
                    </button>
                </div>
            </div>
            <!-- Modal List Keperluan -->
            <div class="modal fade" id="modalListKeperluan" tabindex="-1" aria-labelledby="modalListKeperluanLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalListKeperluanLabel">List Jenis Keperluan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="tableListKeperluan">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:40px;">No</th>
                                            <th class="text-center">Nama Keperluan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($list_keperluan)): ?>
                                            <?php foreach ($list_keperluan as $i => $k): ?>
                                                <tr>
                                                    <td class="text-center"><?= $i + 1 ?></td>
                                                    <td><?= $k->nama_keperluan ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="2" class="text-center">Tidak ada data</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Cetak Langsung oleh Admin -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Cetak Surat Pengantar Anggota Keluarga</h5>
            <form id="formCetakLangsung">
                <div class="row g-2 align-items-center mb-0">
                    <div class="col-md-3">
                        <select class="form-select" id="pjCetak" name="pjCetak">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            <?php if (!empty($pj)): ?>
                                <?php foreach ($pj as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->nama_pj ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="anggotaCetak" name="anggotaCetak" disabled>
                            <option value="">-- Pilih Anggota Keluarga --</option>
                            <?php foreach ($anggota as $a): ?>
                                <option value="<?= $a->uuid ?>"><?= $a->nama ?> (NIK: <?= $a->nik_anggota ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="keperluanCetak" name="keperluanCetak">
                            <option value="">-- Pilih Keperluan Surat --</option>
                            <?php if (!empty($list_keperluan)): ?>
                                <?php foreach ($list_keperluan as $k): ?>
                                    <option value="<?= $k->uuid ?>"><?= $k->nama_keperluan ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="button" class="btn btn-primary" id="btnCetakLangsung">
                            <i class="fas fa-print me-2"></i>Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Surat Menunggu Verifikasi -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Surat Menunggu Verifikasi</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableSuratMenunggu">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Surat</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nama Kepala Keluarga</th>
                            <th class="text-center">Keperluan</th>
                            <th class="text-center">Tanggal Pengajuan</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($surat_menunggu) && count($surat_menunggu)): ?>
                            <?php foreach ($surat_menunggu as $i => $s): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td class="text-center"><?= $s -> no_surat ?></td>
                                    <td class="text-center"><?= $s->nama_anggota ?></td>
                                    <td class="text-center"><?= $s->nama_pj ?? '-' ?></td>
                                    <td class="text-center"><?= $s->jenis_surat ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($s->tanggal_pengajuan)) ?></td>
                                    <td class="text-center">
                                        <?php if ($s->status_proses === 'Diproses'): ?>
                                            <span class="badge bg-warning">Diproses</span>
                                        <?php elseif ($s->status_proses === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($s->status_proses === 'Ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm btn-terima-surat" data-id="<?= $s->id ?>" title="Terima"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger btn-sm btn-tolak-surat" data-id="<?= $s->id ?>" title="Tolak"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Terverifikasi -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Surat Terverifikasi</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableSuratTerverifikasi">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Surat</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nama Kepala Keluarga</th>
                            <th class="text-center">Keperluan</th>
                            <th class="text-center">Tanggal Verifikasi</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($surat_terverifikasi) && count($surat_terverifikasi)): ?>
                            <?php foreach ($surat_terverifikasi as $i => $s): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td class="text-center"><?= $s->no_surat ?></td>
                                    <td class="text-center"><?= $s->nama_anggota ?></td>
                                    <td class="text-center"><?= $s->nama_pj ?? '-' ?></td>
                                    <td class="text-center"><?= $s->jenis_surat ?></td>
                                    <td class="text-center"><?= isset($s->tanggal_verifikasi) ? ($s->tanggal_verifikasi ? date('d/m/Y', strtotime($s->tanggal_verifikasi)) : '-') : '-' ?></td>
                                    <td class="text-center">
                                        <?php if (isset($s->status_proses) && $s->status_proses === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif (isset($s->status_proses) && $s->status_proses === 'Ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (isset($s->status_proses) && $s->status_proses === 'Diterima'): ?>
                                            <button
                                                onclick="window.open('<?= base_url('dashboard/surat/print/anggota/') . ($s->anggota_keluarga_uuid ?? $s->uuid ?? $s->anggota_keluarga_id) . '/' . ($s->keperluan_uuid ?? $s->keperluan_id) ?>', '_blank')"
                                                class="btn btn-primary btn-sm" title="Cetak Surat">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" title="Tidak dapat mencetak surat" disabled style="pointer-events: none; opacity: 0.6;">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
        $('#tableSuratMenunggu, #tableSuratTerverifikasi').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "Tidak ada data yang tersedia pada tabel",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(disaring dari total _MAX_ data)",
                "thousands": ",",
                "lengthMenu": "Tampilkan _MENU_ data",
                "loadingRecords": "Memuat...",
                "processing": "Memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": aktifkan untuk mengurutkan kolom secara menaik",
                    "sortDescending": ": aktifkan untuk mengurutkan kolom secara menurun"
                }
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const pjCetak = document.getElementById('pjCetak');
        const anggotaCetak = document.getElementById('anggotaCetak');
        pjCetak.addEventListener('change', function() {
            anggotaCetak.innerHTML = '<option value="">-- Pilih Anggota Keluarga --</option>';
            anggotaCetak.disabled = true;
            const pjId = this.value;
            if (!pjId) return;
            fetch('<?= base_url('dashboard/surat/get_anggota_by_pj/') ?>' + pjId)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        data.forEach(a => {
                            const opt = document.createElement('option');
                            opt.value = a.uuid ? a.uuid : a.id;
                            opt.textContent = `${a.nama} (NIK: ${a.nik_anggota})`;
                            anggotaCetak.appendChild(opt);
                        });
                        anggotaCetak.disabled = false;
                    } else {
                        anggotaCetak.disabled = true;
                    }
                });
        });

        document.getElementById('btnCetakLangsung').addEventListener('click', function () {
            const pjUuid = document.getElementById('pjCetak').value;
            const anggotaUuid = document.getElementById('anggotaCetak').value;
            const keperluanUuid = document.getElementById('keperluanCetak').value;

            if (!pjUuid || !keperluanUuid) {
                Swal.fire('Peringatan', 'Pilih penanggung jawab dan keperluan surat!', 'warning');
                return;
            }

            let displayName = anggotaUuid
                ? document.getElementById('anggotaCetak').selectedOptions[0].textContent
                : document.getElementById('pjCetak').selectedOptions[0].textContent;

            Swal.fire({
                title: 'Cetak Surat?',
                text: 'Apakah Anda yakin ingin mencetak surat pengantar untuk ' + displayName + '?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const baseUrl = '<?= base_url() ?>';
                    const url = anggotaUuid 
                        ? `${baseUrl}dashboard/surat/print/anggota/${anggotaUuid}/${keperluanUuid}`
                        : `${baseUrl}dashboard/surat/print/pj/${pjUuid}/${keperluanUuid}`;
                    window.open(url, '_blank');
                }
            });
        });




        // Tambah Jenis Keperluan
        const inputJenisKeperluan = document.getElementById('inputJenisKeperluan');
        const btnTambahKeperluan = document.getElementById('btnTambahKeperluan');

        btnTambahKeperluan.addEventListener('click', function() {
            const nama = inputJenisKeperluan.value.trim();
            if (!nama) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Nama keperluan wajib diisi!'
                });
                return;
            }
            btnTambahKeperluan.disabled = true;
            fetch('<?= base_url('keperluan/store') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'nama_keperluan=' + encodeURIComponent(nama)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    inputJenisKeperluan.value = '';
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Jenis keperluan berhasil ditambahkan!'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.error || 'Gagal menambah keperluan'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan jaringan'
                });
            })
            .finally(() => {
                btnTambahKeperluan.disabled = false;
            });
        });

        document.querySelectorAll('.btn-terima-surat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Verifikasi Surat',
                    text: 'Yakin ingin menerima surat ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Terima',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('<?= base_url('dashboard/surat/verifikasi') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(id) + '&status=Diterima'
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire('Berhasil', 'Surat berhasil diverifikasi', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', res.error || 'Gagal memverifikasi surat', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Gagal', 'Terjadi kesalahan jaringan', 'error');
                        });
                    }
                });
            });
        });

        document.querySelectorAll('.btn-tolak-surat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Tolak Surat',
                    text: 'Yakin ingin menolak surat ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('<?= base_url('dashboard/surat/verifikasi') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(id) + '&status=Ditolak'
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire('Ditolak', 'Surat berhasil ditolak', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', res.error || 'Gagal menolak surat', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Gagal', 'Terjadi kesalahan jaringan', 'error');
                        });
                    }
                });
            });
        });
    });
</script>