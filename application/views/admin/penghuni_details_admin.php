<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Detail Data Penghuni</h4>
                <a href="<?= base_url('dashboard/penghuni/view') ?>" class="btn btn-danger">Kembali</a>
            </div>

            <div class="row">
                <!-- Foto dan KTP -->
                <div class="col-md-6 mb-4">
                    <div class="text-center mb-4">
                        <h5>Foto Profil</h5>
                        <?php if ($penghuni->foto_profil): ?>
                            <img src="<?= base_url('uploads/' . $penghuni->foto_profil) ?>" alt="Foto Profil" class="img-fluid rounded" style="max-height: 200px;">
                        <?php else: ?>
                            <p class="text-muted">Foto tidak tersedia</p>
                        <?php endif; ?>
                    </div>
                    <div class="text-center">
                        <h5>Foto KTP</h5>
                        <?php if ($penghuni->foto_ktp): ?>
                            <img src="<?= base_url('uploads/' . $penghuni->foto_ktp) ?>" alt="Foto KTP" class="img-fluid rounded" style="max-height: 200px;">
                        <?php else: ?>
                            <p class="text-muted">Foto KTP tidak tersedia</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informasi Detail -->
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th width="200">Status Verifikasi</th>
                                <td>
                                    <?php
                                    $badge_class = [
                                        'Diproses' => 'bg-warning',
                                        'Diterima' => 'bg-success',
                                        'Ditolak' => 'bg-danger'
                                    ];
                                    ?>
                                    <span class="badge <?= $badge_class[$penghuni->status_verifikasi] ?>">
                                        <?= $penghuni->status_verifikasi ?>
                                    </span>
                                </td>
                            </tr>
                            <?php if ($penghuni->status_verifikasi == 'Ditolak' && $penghuni->alasan): ?>
                            <tr>
                                <th>Alasan Penolakan</th>
                                <td><?= $penghuni->alasan ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>NIK</th>
                                <td><?= $penghuni->nik ?></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td><?= $penghuni->nama_lengkap ?></td>
                            </tr>
                            <tr>
                                <th>Tempat, Tanggal Lahir</th>
                                <td><?= $penghuni->tempat_lahir ?>, <?= date('d/m/Y', strtotime($penghuni->tanggal_lahir)) ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td><?= $penghuni->jenis_kelamin ?></td>
                            </tr>
                            <tr>
                                <th>Golongan Darah</th>
                                <td><?= $penghuni->golongan_darah ?></td>
                            </tr>
                            <tr>
                                <th>Agama</th>
                                <td><?= $penghuni->agama ?></td>
                            </tr>
                            <tr>
                                <th>Provinsi Asal</th>
                                <td><?= $penghuni->provinsi_asal ?></td>
                            </tr>
                            <tr>
                                <th>Kabupaten Asal</th>
                                <td><?= $penghuni->kabupaten_asal ?></td>
                            </tr>
                            <tr>
                                <th>Kecamatan Asal</th>
                                <td><?= $penghuni->kecamatan_asal ?></td>
                            </tr>
                            <tr>
                                <th>Kelurahan Asal</th>
                                <td><?= $penghuni->kelurahan_asal ?></td>
                            </tr>
                            <tr>
                                <th>Alamat Asal</th>
                                <td>
                                    <?= $penghuni->alamat_asal ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Alamat Sekarang</th>
                                <td><?= $penghuni->alamat_sekarang ?></td>
                            </tr>
                            <tr>
                                <th>RT / RW</th>
                                <td>
                                    <?= $penghuni->rt ?> /
                                    <?= $penghuni->rw ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Kepala Lingkungan</th>
                                <td>
                                    <?= $penghuni->kaling_nama ?> 
                                </td>
                            </tr>
                            <tr>
                                <th>Penanggung Jawab</th>
                                <td>
                                    <?= $penghuni->pj_nama ?> 
                                </td>
                            </tr>
                            <tr>
                                <th>Wilayah</th>
                                <td>
                                    <?= $penghuni->wilayah ?> 
                                </td>
                            </tr>
                            <tr>
                                <th>Koordinat Lokasi</th>
                                <td>
                                    <?= $penghuni->latitude ?>, <?= $penghuni->longitude ?>
                                    <div id="map" style="height: 200px;" class="mt-2"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Tujuan</th>
                                <td><?= $penghuni->tujuan ?></td>
                            </tr>
                            <tr>
                                <th>Periode Tinggal</th>
                                <td>
                                    <?= date('d/m/Y', strtotime($penghuni->tanggal_masuk)) ?> - 
                                    <?= date('d/m/Y', strtotime($penghuni->tanggal_keluar)) ?>
                                </td>
                            </tr>
                          
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
    $(document).ready(function() {
        var map = L.map('map').setView([<?= $penghuni->latitude ?>, <?= $penghuni->longitude ?>], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([<?= $penghuni->latitude ?>, <?= $penghuni->longitude ?>])
            .addTo(map)
            .bindPopup('<?= $penghuni->nama_lengkap ?>');
    });
</script>