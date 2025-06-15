<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
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
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                        <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                        <ul class="mb-0 ps-3" style="list-style-type: disc;">
                            <li>Silahkan Memilih Data Anggota yang sesuai dengan<strong> Penanggung Jawab</strong></li>
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
                            <label for="anggota" class="form-label">Pilih Data Anggota Keluarga</label>
                            <select class="form-select" id="anggota" name="anggota_id">
                                <option value="">-- Pilih Data Anggota Keluarga --</option>
                                <?php foreach ($anggota as $a): ?>
                                    <option value="<?= $a->uuid ? $a->uuid : $a->id ?>" data-nama="<?= $a->nama ?>">
                                        <?= $a->nama ?> (NIK: <?= $a->nik_anggota ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="jenisSurat" class="form-label">Pilih Keperluan Surat Pengantar</label>
                            <select class="form-select" id="jenisSurat" name="nama_keperluan">
                                <option value="">-- Pilih Keperluan Surat --</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" id="btnAjukanSurat" disabled>
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                Ajukan Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Menunggu Verifikasi -->
    <div class="card mt-4">
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
                                    <td class="text-center"><?= $s->nama_anggota ?></td>
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
                                        <?php if ($s->status_proses === 'Diterima'): ?>
                                            <a href="<?= base_url('dashboard/surat/cetak_pengantar/' . $s->uuid) ?>" class="btn btn-primary btn-sm" title="Cetak Surat" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" title="Belum bisa cetak" disabled style="pointer-events: none; opacity: 0.6;">
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
                                    <td class="text-center"><?= $s->nama_anggota ?></td>
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
        const anggotaSelect = document.getElementById('anggota');
        const jenisSuratSelect = document.getElementById('jenisSurat'); 
        const btnAjukanSurat = document.getElementById('btnAjukanSurat');

        function checkForm() {
            if (!anggotaSelect || !jenisSuratSelect || !btnAjukanSurat) return;
            btnAjukanSurat.disabled = !anggotaSelect.value || !jenisSuratSelect.value;
        }

        checkForm();

        if (anggotaSelect) anggotaSelect.addEventListener('change', checkForm);
        if (jenisSuratSelect) jenisSuratSelect.addEventListener('change', checkForm);

        if (btnAjukanSurat) {
            btnAjukanSurat.addEventListener('click', function() {
                const form = document.getElementById('suratForm');
                const formData = new FormData(form);

                if (!formData.get('anggota_id') || !formData.get('nama_keperluan')) {
                    Swal.fire('Peringatan', 'Pilih data anggota dan keperluan!', 'warning');
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
                        fetch('<?= base_url('dashboard/surat/ajukan_surat_anggota') ?>', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: new URLSearchParams({
                                    anggota_id: formData.get('anggota_id'),
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
                            // Sudah ditangani di atas, tapi log ke konsol untuk debug
                            console.error('AJAX error:', err);
                        })
                        .finally(() => {
                            btnAjukanSurat.disabled = false;
                        });
                    }
                });
            });
        }

        function loadKeperluan() {
            const combo = document.getElementById('jenisSurat');
            if (!combo) return;
            fetch('<?= base_url('keperluan') ?>')
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        combo.innerHTML = '<option value="">-- Pilih Keperluan Surat --</option>';
                        data.forEach(k => {
                            const opt = document.createElement('option');
                            opt.value = k.id;
                            opt.textContent = k.nama_keperluan;
                            combo.appendChild(opt);
                        });
                    } else {
                        combo.innerHTML = '<option value="" disabled selected>-- Tidak ada keperluan tersedia --</option>';
                    }
                    // Setelah load, cek ulang form
                    checkForm();
                });
        }
        loadKeperluan();
    });
</script>