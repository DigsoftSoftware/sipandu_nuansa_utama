<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">

    <!-- Tabel Perhatian dan Tambah Keperluan -->
    <div class="card mb-4">
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

    <!-- Form Cetak Langsung oleh Admin -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Cetak Surat Pengantar Pendatang</h5>
            <form id="formCetakLangsungPendatang">
                <div class="row g-2 align-items-center mb-0">
                    <div class="col-md-3">
                        <select class="form-select" id="pjCetakPendatang" name="pjCetakPendatang">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            <?php if (!empty($pj)): ?>
                                <?php foreach ($pj as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->nama_pj ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="penghuniCetak" name="penghuniCetak" disabled>
                            <option value="">-- Pilih Data Pendatang --</option>
                            <?php foreach ($penghuni as $p): ?>
                                    <?php if ($p->status_penghuni === 'Aktif'): ?>
                                        <option value="<?= $p->uuid ?>"><?= $p->nama_lengkap ?> (NIK: <?= $p->nik ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                        </select>
                    </div>
                   <div class="col-md-3">
                        <select class="form-select" id="keperluanCetakPendatang" name="keperluanCetakPendatang">
                            <option value="">-- Pilih Keperluan Surat --</option>
                            <option value="Membuat KK">Membuat KK</option>
                            <option value="Mengurus KTP">Mengurus KTP</option>
                            
                            <?php if (!empty($list_keperluan)): ?>
                                <?php foreach ($list_keperluan as $k): ?>
                                    <option value="<?= $k->keperluan ?>"><?= $k->keperluan ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <option value="lainnya">Lainnya...</option>
                        </select>
                    </div>

                    <div class="col-md-3 mt-2" id="inputKeperluanLainnya" style="display: none;">
                        <label for="keperluanLainnya">Tulis Keperluan Lainnya</label>
                        <input type="text" class="form-control" name="keperluanLainnya" id="keperluanLainnya" placeholder="Tulis keperluan lainnya...">
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="button" class="btn btn-primary" id="btnCetakLangsungPendatang">
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
                            <th class="text-center">Nama Pendatang</th>
                            <th class="text-center">Penanggung Jawab</th>
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
                                    <td class="text-center"><?= $s->nama_pj ?></td>
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
                            <th class="text-center">Nama</th>
                            <th class="text-center">Penanggung Jawab</th>
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
                                    <td class="text-center"><?= $s->nama_penghuni ?></td>
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
                                            <a href="<?= base_url('dashboard/surat/print/pendatang/' . ($s->penghuni_uuid ?? $s->uuid ?? $s->penghuni_id) . '/' . ($s->keperluan_uuid ?? $s->keperluan_id)) ?>"
                                                class="btn btn-primary btn-sm" title="Cetak Surat" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        <?php elseif (isset($s->status_proses) && $s->status_proses === 'Ditolak'): ?>
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
    document.addEventListener('DOMContentLoaded', function () {
        // DataTable Init
        $('#tableSuratMenunggu, #tableSuratTerverifikasi').DataTable({
            language: {
                decimal: "",
                emptyTable: "Tidak ada data yang tersedia pada tabel",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari total _MAX_ data)",
                thousands: ",",
                lengthMenu: "Tampilkan _MENU_ data",
                loadingRecords: "Memuat...",
                processing: "Memproses...",
                search: "Cari:",
                zeroRecords: "Tidak ditemukan data yang cocok",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                },
                aria: {
                    sortAscending: ": aktifkan untuk mengurutkan kolom secara menaik",
                    sortDescending: ": aktifkan untuk mengurutkan kolom secara menurun"
                }
            }
        });

        const penanggungJawabSelect = document.getElementById('penanggung_jawab_id');
        const penghuniSelect = document.getElementById('penghuni');
        const jenisSuratSelect = document.getElementById('jenisSurat');
        const btnCetakSurat = document.getElementById('btnCetakSurat');
        const btnAjukanSurat = document.getElementById('btnAjukanSurat');
        const suratForm = document.getElementById('suratForm');

        function checkFormCetak() {
            btnCetakSurat.disabled = !(penanggungJawabSelect.value && penghuniSelect.value && jenisSuratSelect.value);
        }

        function checkFormAjukan() {
            btnAjukanSurat.disabled = !(penghuniSelect.value && jenisSuratSelect.value);
        }

        penanggungJawabSelect?.addEventListener('change', function () {
            const pjId = this.value;
            penghuniSelect.innerHTML = '<option value="">-- Pilih Data Pendatang --</option>';
            if (!pjId) return checkFormCetak();

            fetch(`<?= base_url('dashboard/surat/get_penghuni_by_pj/') ?>${pjId}`)
                .then(res => res.ok ? res.json() : Promise.reject())
                .then(data => {
                    data.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.uuid;
                        opt.dataset.nama = p.nama_lengkap;
                        opt.textContent = `${p.nama_lengkap} (NIK: ${p.nik})`;
                        penghuniSelect.appendChild(opt);
                    });
                    checkFormCetak();
                })
                .catch(() => {
                    Swal.fire('Error', 'Gagal mengambil data pendatang', 'error');
                });
        });

        penghuniSelect?.addEventListener('change', checkFormCetak);
        jenisSuratSelect?.addEventListener('change', () => {
            checkFormCetak();
            checkFormAjukan();
        });

        btnCetakSurat?.addEventListener('click', function () {
            const uuid = penghuniSelect.value;
            const jenisSurat = jenisSuratSelect.value;
            const nama = penghuniSelect.options[penghuniSelect.selectedIndex]?.dataset?.nama;
            if (!uuid || !jenisSurat) return;

            const isSIT = jenisSurat === 'izin_tinggal';
            const url = isSIT
                ? '<?= base_url('dashboard/surat/SIT/') ?>' + uuid
                : '<?= base_url('dashboard/surat/SP/') ?>' + uuid;

            Swal.fire({
                title: 'Cetak ' + (isSIT ? 'Surat Izin Tinggal' : 'Surat Pernyataan'),
                text: `Apakah anda ingin mencetak surat untuk ${nama}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) window.open(url, '_blank');
            });
        });

       
        // Verifikasi surat (terima/tolak)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-terima-surat, .btn-tolak-surat');
            if (!btn) return;
            const id = btn.dataset.id;
            const isTerima = btn.classList.contains('btn-terima-surat');
            const status = isTerima ? 'Diterima' : 'Ditolak';

            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda yakin ingin ${isTerima ? 'menerima' : 'menolak'} surat ini?`,
                icon: isTerima ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonText: `Ya, ${isTerima ? 'Terima' : 'Tolak'}`,
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('<?= base_url('dashboard/surat/verifikasi') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${encodeURIComponent(id)}&status=${status}`
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire('Berhasil', `Surat berhasil ${status.toLowerCase()}!`, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', res.error || 'Gagal memproses surat', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan jaringan', 'error'));
                }
            });
        });

        // Admin: PJ Cetak Pendatang
        const pjCetakPendatang = document.getElementById('pjCetakPendatang');
        const penghuniCetak = document.getElementById('penghuniCetak');
        const btnCetakLangsungPendatang = document.getElementById('btnCetakLangsungPendatang');
        const keperluanCetakPendatang = document.getElementById('keperluanCetakPendatang');

        pjCetakPendatang?.addEventListener('change', function () {
            const pjId = this.value;
            penghuniCetak.innerHTML = '<option value="">-- Pilih Data Pendatang --</option>';
            penghuniCetak.disabled = true;
            if (!pjId) return;

            fetch('<?= base_url('dashboard/surat/get_penghuni_by_pj/') ?>' + pjId)
                .then(res => res.json())
                .then(data => {
                    if (data?.length > 0) {
                        data.forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.uuid || p.id;
                            opt.textContent = `${p.nama_lengkap} (NIK: ${p.nik})`;
                            penghuniCetak.appendChild(opt);
                        });
                        penghuniCetak.disabled = false;
                    }
                });
        });

        btnCetakLangsungPendatang?.addEventListener('click', function () {
            const uuid = penghuniCetak.value;
            const keperluan = keperluanCetakPendatang.value;
            const label = penghuniCetak.options[penghuniCetak.selectedIndex]?.textContent || '';

            if (!uuid || !keperluan) return Swal.fire('Peringatan', 'Pilih data pendatang dan keperluan!', 'warning');

            Swal.fire({
                title: 'Cetak Surat?',
                text: `Cetak surat pengantar untuk ${label}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    window.open('<?= base_url('dashboard/surat/print/pendatang/') ?>' + uuid + '/' + keperluan, '_blank');
                }
            });
        });

        // Inisialisasi awal
        loadKeperluan();
        loadKeperluanList();
    });
</script>
