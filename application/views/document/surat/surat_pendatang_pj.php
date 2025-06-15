<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">Surat Pengantar Pendatang</h5>
                <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/surat/view') ?>'">Kembali</button>
            </div>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                        <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                        <ul class="mb-0 ps-3" style="list-style-type: disc;">
                            <li>Silahkan Memilih Data Pendatang yang sudah terverifikasi oleh <strong>Kepala Lingkungan</strong></li>
                            <li>Gunakan format kertas <strong>F4 atau Legal</strong> saat mencetak dokumen</li>
                            <li>Pastikan skala cetak dokumen disetel ke <strong>100%</strong> agar format tidak terpotong</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="suratForm">
                        <div class="form-group mb-4">
                            <label for="penghuni" class="form-label">Pilih Data Pendatang</label>
                            <select class="form-select" id="penghuni" name="penghuni">
                                <option value="">-- Pilih Data Pendatang --</option>
                                <?php if (!empty($penghuni)): ?>
                                    <?php foreach ($penghuni as $p): ?>
                                        <option value="<?= $p->uuid ?>" data-nama="<?= $p->nama_lengkap ?>">
                                            <?= $p->nama_lengkap ?> (NIK: <?= $p->nik ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">Tidak ada data pendatang</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="jenisSurat" class="form-label">Pilih Keperluan Surat Pengantar</label>
                            <select class="form-select" id="jenisSurat" name="nama_keperluan">
                                <option value="">-- Pilih Keperluan Surat --</option>
                                <?php if (!empty($list_keperluan)): ?>
                                    <?php foreach ($list_keperluan as $k): ?>
                                        <option value="<?= $k->uuid ?>"><?= $k->nama_keperluan ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" id="btnAjukanSurat">
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                Ajukan Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Pengantar -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Pengajuan Surat Pengantar Pendatang</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableSuratMenunggu">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Surat</th>
                            <th class="text-center">Nama</th>
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
                                    <td class="text-center"><?= $s->no_surat ?></td>
                                    <td class="text-center"><?= $s->nama_penghuni ?></td>
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
                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm<?php if ($s->status_proses !== 'Diterima') echo ' disabled'; ?>"
                                            title="Cetak Surat"
                                            onclick="window.open('<?= base_url('dashboard/surat/cetak_pengantar/' . $s->uuid) ?>', '_blank')"
                                            tabindex="<?= $s->status_proses === 'Diterima' ? '0' : '-1' ?>"
                                            aria-disabled="<?= $s->status_proses !== 'Diterima' ? 'true' : 'false' ?>"
                                            <?php if ($s->status_proses !== 'Diterima') echo 'style=\"pointer-events:none;opacity:0.5;\"'; ?>>
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Terverifikasi (Diterima/Ditolak) -->
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
                            <th class="text-center">Nama</th>
                            <th class="text-center">Keperluan</th>
                            <th class="text-center">Tanggal Pengajuan</th>
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
                                    <td class="text-center"><?= $s->nama_penghuni ?></td>
                                    <td class="text-center"><?= $s->jenis_surat ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($s->tanggal_pengajuan)) ?></td>
                                    <td class="text-center">
                                        <?php if ($s->status_proses === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($s->status_proses === 'Ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('dashboard/surat/print/pendatang/' . ($s->penghuni_uuid ?? $s->uuid ?? $s->penghuni_id) . '/' . ($s->keperluan_uuid ?? $s->keperluan_id)) ?>"
                                                class="btn btn-primary btn-sm" title="Cetak Surat" target="_blank">
                                                <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
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
        const penghuniSelect = document.getElementById('penghuni');
        const jenisSuratSelect = document.getElementById('jenisSurat');
        const btnAjukanSurat = document.getElementById('btnAjukanSurat');

        function checkForm() {
            if (!penghuniSelect || !jenisSuratSelect || !btnAjukanSurat) return;
            btnAjukanSurat.disabled = !penghuniSelect.value || !jenisSuratSelect.value;
        }

        checkForm();

        if (penghuniSelect) penghuniSelect.addEventListener('change', checkForm);
        if (jenisSuratSelect) jenisSuratSelect.addEventListener('change', checkForm);

        if (btnAjukanSurat) {
            btnAjukanSurat.addEventListener('click', function() {
                const form = document.getElementById('suratForm');
                const formData = new FormData(form);

                if (!formData.get('penghuni') || !formData.get('nama_keperluan')) {
                    Swal.fire('Peringatan', 'Pilih data pendatang dan keperluan!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Ajukan Surat?',
                    text: 'Yakin ingin mengajukan surat ini ke admin?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ajukan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btnAjukanSurat.disabled = true;
                        fetch('<?= base_url('dashboard/surat/ajukan_surat_pendatang_pj') ?>', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: new URLSearchParams({
                                    penghuni: formData.get('penghuni'),
                                    nama_keperluan: formData.get('nama_keperluan')
                                })
                            })
                            .then(async res => {
                                let data;
                                if (!res.ok) {
                                    const text = await res.text();
                                    Swal.fire('Gagal', 'Terjadi kesalahan server:<br><pre style="text-align:left">' + text + '</pre>', 'error');
                                    throw new Error('Server error: ' + text);
                                }
                                try {
                                    data = await res.json();
                                } catch (e) {
                                    const text = await res.text();
                                    Swal.fire('Gagal', 'Terjadi kesalahan server:<br><pre style="text-align:left">' + text + '</pre>', 'error');
                                    throw new Error('Server error');
                                }
                                return data;
                            })
                            .then(res => {
                                if (res && res.success) {
                                    Swal.fire('Berhasil', 'Pengajuan surat berhasil dikirim ke admin', 'success').then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire('Gagal', (res && res.error) || 'Gagal mengajukan surat', 'error');
                                }
                            })
                            .catch((err) => {
                                console.error('AJAX error:', err);
                            })
                            .finally(() => {
                                btnAjukanSurat.disabled = false;
                            });
                    }
                });
            });
        }
    });
</script>