<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>
<style>
    .custom-div-icon {
        background: none;
        border: none;
    }

    .leaflet-div-icon {
        background: transparent;
        border: none;
    }

    .marker-cluster-small {
        background-color: rgba(77, 115, 223, 0.6);
    }

    .marker-cluster-small div {
        background-color: rgba(77, 115, 223, 0.6);
    }

    .marker-cluster span {
        color: white;
    }

    .card-body h5 {
        color: white;
    }

    .card-body h3 {
         font-size: 2rem; 
        color: white;
    }

</style>
<!-- Main Content -->
<div class="container-fluid">
    <div class="mb-4">
        <h2>Selamat Datang di Dashboard, <?= $this->session->userdata('nama'); ?></h2>
    </div>
    <div class="row mb-4">
       <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary shadow rounded-3">
                <div class="card-body d-flex flex-column justify-content-between" style="height: 100%;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Data Kaling</h5>
                            <h3 class="mb-0"><?= $total_kaling; ?></h3>
                        </div>
                        <i class="fas fa-user fa-3x"></i>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalKaling">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success shadow rounded-3">
                <div class="card-body d-flex flex-column justify-content-between" style="height: 100%;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Data PJ</h5>
                            <h3 class="mb-0"><?= $total_pj; ?></h3>
                        </div>
                        <i class="fas fa-user-friends fa-3x"></i>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalPJ">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </button>
                    </div>
                </div> 
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning shadow rounded-3">
                <div class="card-body d-flex flex-column justify-content-between" style="height: 100%;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Data Warga</h5>
                            <h3 class="mb-0"><?= $total_warga; ?></h3>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalWarga">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger shadow rounded-3">
                <div class="card-body d-flex flex-column justify-content-between" style="height: 100%;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Data Pendatang</h5>
                            <h3 class="mb-0"><?= $total_users; ?></h3>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalPendatang">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow rounded-3">
                <div class="card-body">
                    <h4 class="card-title fw-semibold mb-4">Peta Data Pendatang</h4>
                    <div id="map" style="height: 400px; z-index: 1; width: 100%;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow rounded-3">
                <div class="card-body">
                    <h4 class="card-title fw-semibold mb-4">Pendatang Diverifikasi Bulan Ini</h4>
                    <div class="alert alert-primary border-0 shadow-sm rounded-3" role="alert">
                        <ul class="mb-0 ps-3" style="list-style-type: disc;">
                            <li>Data yang ditampilkan hanya data yang sudah <strong> Verifikasi Data Diterima dan Verifikasi Pendatang Aktif</strong> saja.</li>
                        </ul>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="tableVerifikasi">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pendatang</th>
                                    <th>Nama Penanggung Jawab</th>
                                    <th>Status Verifikasi</th>
                                    <th>Status Pendatang</th>
                                    <th>Tanggal Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($penghuni_verifikasi_bulanini as $p): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($p['nama_lengkap']); ?></td>
                                    <td><?= htmlspecialchars($p['nama_pj']); ?></td>
                                    <td>
                                        <?php
                                            $status = htmlspecialchars($p['status_verifikasi']);
                                            $badgeClasses = [
                                                'Diterima' => 'badge bg-success',
                                                'Diproses' => 'badge bg-warning text-dark',
                                                'Ditolak'  => 'badge bg-danger'
                                            ];
                                            $badgeClass = $badgeClasses[$status] ?? 'badge bg-secondary';
                                        ?>
                                        <span class="<?= $badgeClass; ?>"><?= $status; ?></span>
                                    </td>
                                    <td>
                                        <?php
                                            $status = htmlspecialchars($p['status_penghuni']);
                                            $badgeClasses = [
                                                'Aktif' => 'badge bg-primary',
                                                'Tidak Aktif'  => 'badge bg-danger'
                                            ];
                                            $badgeClass = $badgeClasses[$status] ?? 'badge bg-secondary';
                                        ?>
                                        <span class="<?= $badgeClass; ?>"><?= $status; ?></span>
                                    </td>

                                    <td><?= date('d/m/Y', strtotime($p['tanggal_masuk'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($penghuni_verifikasi_bulanini)) : ?>
                                <tr><td colspan="4" class="text-center">Belum ada verifikasi hari ini.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Modal Data Kaling -->
    <div class="modal fade" id="modalKaling" tabindex="-1" aria-labelledby="modalKalingLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKalingLabel">Detail Data Kaling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                <label for="searchKaling" class="form-label">Cari Nama Kaling:</label>
                <select id="searchKaling" class="form-select select2">
                    <option value="">-- Tampilkan Semua --</option>
                    <?php foreach($data_kaling as $k): ?>
                    <option value="<?= $k->nama; ?>"><?= $k->nama; ?></option>
                    <?php endforeach; ?>
                </select>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="tableKaling">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach($data_kaling as $k): ?>
                        <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $k->nama; ?></td>
                        <td><?= $k->alamat; ?></td>
                        <td><?= $k->no_hp; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>



    <!-- Modal Data Penanggung Jawab -->
    <div class="modal fade" id="modalPJ" tabindex="-1" aria-labelledby="modalPJLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPJLabel">Detail Data Penanggung Jawab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                <label for="searchPJ" class="form-label">Cari Nama Penanggung Jawab:</label>
                <select id="searchPJ" class="form-select select2">
                    <option value="">-- Tampilkan Semua --</option>
                    <?php foreach($data_pj as $p): ?>
                    <option value="<?= $p->nama_pj; ?>"><?= $p->nama_pj; ?></option>
                    <?php endforeach; ?>
                </select>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="tablePJ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php $no = 1; foreach($data_pj as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td> 
                            <td><?= $p->nama_pj; ?></td>
                            <td><?= $p->alamat_detail; ?>, No.<?= $p->alamat_no ?></td>
                            <td><?= $p->no_hp; ?></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>


    <!-- Modal Data Warga -->
   <div class="modal fade" id="modalWarga" tabindex="-1" aria-labelledby="modalWargaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="modalWargaLabel">Detail Data Warga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                <label for="searchWarga" class="form-label">Cari Nama Warga:</label>
                <select id="searchWarga" class="form-select select2">
                    <option value="">-- Tampilkan Semua --</option>
                    <?php foreach($data_warga as $w): ?>
                    <option value="<?= $w->nama_lengkap; ?>"><?= $w->nama_lengkap; ?></option>
                    <?php endforeach; ?>
                </select>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="tableWarga">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Hubungan</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php $no = 1; foreach($data_warga as $w): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $w->nama_lengkap; ?></td>
                            <td><?= $w->alamat; ?></td>
                            <td><?= $w->status; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Data Pendatang -->
     <div class="modal fade" id="modalPendatang" tabindex="-1" aria-labelledby="modalPendatangLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPendatangLabel">Detail Pendatang Aktif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="searchPendatang" class="form-label">Cari Nama Pendatang:</label>
                    <select id="searchPendatang" class="form-select select2">
                        <option value="">-- Tampilkan Semua --</option>
                        <?php foreach($data_pendatang as $pd): ?>
                        <option value="<?= $pd->nama_lengkap; ?>"><?= $pd->nama_lengkap; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="table-responsive">
                <table class="table table-bordered" id="tablePendatang">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pendatang</th>
                            <th>Nama Penanggung Jawab</th>
                            <th>Alamat</th>
                            <th>No Handphone</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach($data_pendatang as $pd): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $pd->nama_lengkap; ?></td>
                            <td><?= $pd->nama_pj; ?></td>
                            <td><?= $pd->alamat_sekarang; ?></td>
                            <td><?= $pd->no_hp; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
        $('#tableVerifikasi').DataTable({
            language: {
                decimal: "",
                emptyTable: "Tidak ada data yang tersedia pada tabel",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari total _MAX_ data)",
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

        $('.select2').each(function() {
            $(this).select2({
                dropdownParent: $(this).closest('.modal'),
                width: '100%'
            });
        });

        function applyFilter(selectId, tableId) {
            $(selectId).on('change', function() {
                const selected = $(this).val().toLowerCase();
                $(`${tableId} tbody tr`).each(function() {
                const name = $(this).find('td:nth-child(2)').text().toLowerCase();
                $(this).toggle(!selected || name.includes(selected));
                });
            });
        }

        applyFilter('#searchKaling', '#tableKaling');
        applyFilter('#searchPJ', '#tablePJ');
        applyFilter('#searchWarga', '#tableWarga');
        applyFilter('#searchPendatang', '#tablePendatang');
    });

    const map = L.map('map').setView([-8.79878541, 115.18576026], 16); 

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.control.scale().addTo(map);
    const penghuniBaru = <?= json_encode($penghuni_baru) ?>;
    const markers = L.markerClusterGroup();
    penghuniBaru.forEach(function (p) {
        const warna = p.status_penghuni === 'lama' ? '#6c757d' : '#4e73df';
        const customIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: ${warna}; color: white; padding: 5px; border-radius: 5px; font-size: 12px;">${p.nama}</div>`,
            iconSize: [200, 20],
            iconAnchor: [100, 10]
        });
        const marker = L.marker([p.lat, p.lng]) 
            .bindPopup(`
                <div style="text-align: center;">
                    <strong>${p.nama}</strong><br>
                    ${p.nik ? `NIK: ${p.nik}<br>` : ''}
                    ${p.status ? `Status: ${p.status}` : ''}
                </div>
            `);

        markers.addLayer(marker);
    });

    map.addLayer(markers);
</script>
