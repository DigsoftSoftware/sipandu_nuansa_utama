<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            
          <?php if ($this->session->flashdata('error')): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: <?= json_encode($this->session->flashdata('error')) ?>,
                });
            </script>
        <?php endif; ?>

        <?php if (validation_errors()): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    text: <?= json_encode(strip_tags(validation_errors())) ?>,
                });
            </script>
        <?php endif; ?>

            <form action="<?= base_url('dashboard/pj/update_pj/' . $pj->uuid) ?>" method="POST" enctype="multipart/form-data" id="pjForm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Profil Data</h4>
                </div>

                <!-- Alert Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                            <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                            <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                <li>Pastikan Anda mengunggah <strong>Scan Kartu Keluarga</strong> dengan jelas.</li>
                                <li>Data seperti <em>NIK</em>, <em>Nama</em>, <em>Tempat/Tanggal Lahir</em>, dan lainnya harus diisi manual dan bisa otomatis sesuai KK.</li>
                                <li>Jangan lupa untuk <strong>klik lokasi</strong> Anda pada peta untuk mengisi koordinat.</li>
                                <li><strong>Alamat Sekarang</strong> akan terisi otomatis berdasarkan GPS browser Anda.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- FOTO & SCAN KK -->
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <label class="form-label">KK (Kartu Keluarga)</label><br>
                        <?php if (!empty($pj->foto_kk)): ?>
                            <img src="<?= base_url('uploads/pj/' . $pj->foto_kk) ?>" alt="Scan KK" class="img-fluid" style="max-height: 400px;">
                        <?php else: ?>
                            <img src="<?= base_url('assets/images/profile/scan_kk.png') ?>" alt="Scan KK" class="img-fluid" style="max-height: 400px; filter: grayscale(100%);">
                        <?php endif; ?>
                        <div class="mt-4">
                            <input type="file" name="foto_kk" id="foto_kk" class="form-control" accept="image/*,.pdf">
                        </div>
                        <button type="button" class="btn btn-primary mt-4 text-center" onclick="showPreviewImage('kk', 'previewKK', 'modalPreviewKK')">Preview KK</button>
                    </div>
                </div>

                <div class="row">
                    <!-- KOLOM KIRI -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No KK (Isi Sesuai KK)</label>
                            <input type="text" name="no_kk" class="form-control" value="<?=($pj->no_kk) ?>" placeholder="Masukkan No KK">
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIK Kepala Keluarga (Isi Sesuai KK)</label>
                            <input type="text" name="nik" class="form-control" value="<?=($pj->nik) ?>" placeholder="Masukkan NIK Kepala Keluarga">
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?=($pj->username) ?>" placeholder="Masukkan Username">
                            <span class="invalid-feedback"></span>
                        </div>                        
                        <div class="mb-3">
                            <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                            <div class="input-group">
                                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Masukkan Password">
                                <span class="input-group-text cursor-pointer" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="iconPassword"></i>
                                </span>
                            </div>
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?=($pj->email) ?>" placeholder="Masukkan Email">
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kepala Keluarga (Isi Sesuai KK)</label>
                            <input type="text" name="nama_pj" class="form-control"  value="<?=($pj->nama_pj) ?>" placeholder="Masukkan Nama Kepala Keluarga">
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Handphone</label>
                            <input type="number" name="no_hp" class="form-control" value="<?=($pj->no_hp) ?>" placeholder="Masukkan No Handphone">
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir (Isi Sesuai KK)</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="<?=($pj->tempat_lahir) ?>" placeholder="Masukkan Tempat Lahir">
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir (Isi Sesuai KK)</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="<?= $pj->tanggal_lahir ? date('Y-m-d', strtotime($pj->tanggal_lahir)) : '' ?>" placeholder="Pilih Tanggal Lahir">
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select <?= form_error('jenis_kelamin') ? 'is-invalid' : '' ?>" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="LAKI - LAKI" <?= $pj->jenis_kelamin == 'LAKI - LAKI' ? 'selected' : '' ?>>LAKI-LAKI</option>
                                <option value="PEREMPUAN" <?= $pj->jenis_kelamin == 'PEREMPUAN' ? 'selected' : '' ?>>PEREMPUAN</option>
                            </select>
                            <span class="invalid-feedback error-message"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Wilayah</label>
                            <select class="form-select" name="wilayah_id" id="wilayah_id" placeholder="Pilih Wilayah">
                                <option value="">-- Pilih Wilayah --</option>
                                <?php foreach ($wilayah as $w): ?>
                                    <option value="<?= $w->id ?>" <?= $pj->wilayah_id == $w->id ? 'selected' : '' ?>><?= $w->wilayah?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Detail</label>
                            <select class="form-select" name="alamat_detail" id="alamat_detail" placeholder="Pilih Alamat Detail">
                                <option value="">-- Pilih Alamat Detail --</option>
                                <?php 
                                    $jalan_options = [
                                        "JL. NUANSA UTAMA II",
                                        "JL. NUANSA UTAMA IV",
                                        "JL. NUANSA UTAMA BARAT",
                                        "JL. NUANSA UTAMA V",
                                        "JL. NUANSA UTAMA VI",
                                        "JL. NUANSA UTAMA VII",
                                        "JL. NUANSA UTAMA VIII",
                                        "JL. NUANSA UTAMA IX",
                                        "JL. NUANSA UTAMA X",
                                        "JL. NUANSA UTAMA XI",
                                        "JL. NUANSA UTAMA XIA",
                                        "JL. NUANSA UTAMA XII",
                                        "JL. NUANSA UTAMA XIII",
                                        "JL. NUANSA UTAMA XIV",
                                        "JL. NUANSA UTAMA XV",
                                        "JL. NUANSA UTAMA XVI",
                                        "JL. NUANSA UTAMA XVII",
                                        "JL. NUANSA UTAMA XVIII",
                                        "JL. NUANSA UTAMA TENGAH",
                                        "JL. NUANSA UTAMA XIX",
                                        "JL. NUANSA UTAMA XXI",
                                        "JL. NUANSA UTAMA XXIII",
                                        "JL. NUANSA UTAMA XXV",
                                        "JL. NUANSA UTAMA XXVII",
                                        "JL. NUANSA UTAMA RAYA"
                                    ];

                                    foreach ($jalan_options as $jalan) {
                                        echo '<option value="'.$jalan.'" '.($pj->alamat_detail == $jalan ? 'selected' : '').'>'.$jalan.'</option>';
                                    }
                                ?>

                            </select>
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Rumah</label>
                            <select class="form-select" name="alamat_no" id="alamat_no" placeholder="Pilih No Rumah">
                                <option value="">-- Pilih No Rumah --</option>
                                <?php 
                                    for ($i = 1; $i <= 40; $i++) {
                                        echo '<option value="'.$i.'" '.($pj->alamat_no == $i ? 'selected' : '').'>'.$i.'</option>';
                                    }
                                ?>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Status Rumah</label>
                            <?php
                                $options = [
                                    '' => '-- Pilih Status Rumah --',
                                    'Permanen' => 'Permanen',
                                    'Kontrak' => 'Kontrak'
                                ];
                                echo form_dropdown('status_rumah', $options, $pj->status_rumah, 'class="form-select" placeholder="Pilih Status Rumah"');
                            ?>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>

                    <!-- KOLOM KANAN -->
                    <div class="col-md-6">
                        <div class="location-inputs">
                            <div class="mb-3">
                                <label class="form-label">Lokasi (Klik pada peta)</label>
                                <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                            </div>  
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly value="<?=($pj->latitude) ?>">
                                    <span class="invalid-feedback"></span>
                                </div>
    
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly value="<?=($pj->longitude) ?>">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Maps</label>
                                <textarea name="alamat_maps" id="alamat_maps" class="form-control"><?=($pj->alamat_maps) ?></textarea>
                                <span class="invalid-feedback"></span>
                                <button type="button" id="search-location" class="btn btn-warning mt-2 w-100"> <i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi</button>
                            </div>
                        
                        </div>

                        <div class="mb-3">
                            <div class="mb-3">
                                <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
                                    <i class="ti ti-plus"></i> Tambah Anggota Keluarga
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="anggotaTable">
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Tempat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Hubungan</th>
                                            <th>Pekerjaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="anggotaBody">
                                        <?php if (isset($anggota_keluarga) && is_array($anggota_keluarga) && count($anggota_keluarga) > 0): ?>
                                            <?php foreach ($anggota_keluarga as $anggota): ?>
                                                <tr>
                                                    <td><?=($anggota->nik_anggota) ?></td>
                                                    <td><?=($anggota->nama) ?></td>
                                                    <td><?=($anggota->tempat_lahir) ?></td>
                                                    <td><?=($anggota->tanggal_lahir) ?></td>
                                                    <td><?=($anggota->jenis_kelamin) ?></td>
                                                    <td><?=($anggota->hubungan) ?></td>
                                                    <td><?=($anggota->pekerjaan) ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning btn-sm" onclick="editAnggotaByNIK('<?= $anggota->nik_anggota ?>')">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteAnggotaByNIK('<?= $anggota->nik_anggota ?>')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="anggota_keluarga" id="anggota_keluarga_input">
                        </div>
                    </div>
                </div>

                <div class="mt-3 mb-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/penghuni/viewpj') ?>'">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>
                                                
<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-labelledby="modalTambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahAnggotaLabel">Tambah Anggota Keluarga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">NIK Anggota</label>
                    <input type="text" id="nikAnggota" class="form-control" maxlength="17" placeholder="Masukkan NIK Anggota">
                    <span class="invalid-feedback"></span>
                    <small class="text-muted">Masukkan 16 digit NIK sesuai KTP/KK</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="namaAnggota" class="form-control" maxlength="100" placeholder="Masukkan Nama Lengkap">
                    <span class="invalid-feedback"></span>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" id="tempatLahirAnggota" class="form-control" maxlength="20" placeholder="Masukkan Tempat Lahir">
                    <span class="invalid-feedback"></span>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" id="tanggalLahirAnggota" class="form-control" placeholder="Pilih Tanggal Lahir">
                    <span class="invalid-feedback"></span>
                </div>
             
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jeniskelaminAnggota" class="form-select <?= form_error('jenis_kelamin') ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="LAKI - LAKI" <?= $pj->jenis_kelamin == 'LAKI - LAKI' ? 'selected' : '' ?>>LAKI-LAKI</option>
                        <option value="PEREMPUAN" <?= $pj->jenis_kelamin == 'PEREMPUAN' ? 'selected' : '' ?>>PEREMPUAN</option>
                    </select>
                    <span class="invalid-feedback error-message"></span>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hubungan dengan Kepala Keluarga </label>
                        <?php
                                $options = [
                                    '' => '-- Pilih Hubungan --',
                                    'Anak'=> 'Anak',
                                    'Istri'=> 'Istri',
                                    'Suami' => 'Suami',
                                    'Orang Tua' => 'Orang Tua',
                                    'Saudara'=> 'Saudara',
                                    'Lainnya'=> 'Lainnya',
                                ];
                                echo form_dropdown('hubunganAnggota', $options, set_value('hubunganAnggota'), 'class="form-select" id="hubunganAnggota" placeholder="Pilih Hubungan"');
                            ?>   
                </div>
                <div class="mb-3">
                    <label class="form-label">Pekerjaan Anggota</label>
                        <?php
                                $options = [
                                    '' => '-- Pilih Pekerjaan Anggota --',
                                    'Pegawai Swasta'=> 'Pegawai Swasta',
                                    'Wiraswasta'=> 'Wiraswasta',
                                    'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa',
                                    'Mengurus Rumah Tangga' => 'Mengurus Rumah Tangga',
                                    'PNS'=> 'PNS',
                                    'TNI/Polri' => 'TNI/Polri',
                                    'Pensiunan' => 'Pensiunan',
                                    'Belum/Tidak Bekerja'=> 'Belum/Tidak Bekerja',
                                ];
                                echo form_dropdown('pekerjaanAnggota', $options, set_value('pekerjaanAnggota'), 'class="form-select" id="pekerjaanAnggota" placeholder="Pilih Pekerjaan"');
                        ?>                    
                </div>
                <button type="button" class="btn btn-primary" onclick="addAnggota()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview KK -->
<div class="modal fade" id="modalPreviewKK" tabindex="-1" aria-labelledby="modalPreviewKKLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPreviewKKLabel">Preview KK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="previewKKImg" src="#" alt="Preview KK" class="img-fluid rounded shadow-sm" style="max-width: 100%; width: auto;">
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function() {
        initMap();
        window.anggotaKeluarga = <?php echo isset($anggota_keluarga) ? json_encode(array_map(function($a) {
            return [
                'nik' => $a->nik_anggota,
                'nama' => $a->nama, 
                'tempat_lahir' => $a->tempat_lahir,
                'tanggal_lahir' => $a->tanggal_lahir,
                'jenis_kelamin' => $a->jenis_kelamin,
                'hubungan' => $a->hubungan,
                'pekerjaan' => $a->pekerjaan
            ];
        }, $anggota_keluarga)) : '[]'; ?>;
        updateTable();

        $('#togglePassword').on('click', function() {
            const input = $('#inputPassword');
            const icon = $('#iconPassword');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });

        $('#pjForm').on('submit', function(e) {
            e.preventDefault(); 
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            let isValid = true;
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Field ini wajib diisi');
                    isValid = false;
                }
            });
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Error!',
                    text: 'Silakan lengkapi semua field yang wajib diisi',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin ingin menyimpan perubahan data?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));
                    let formData = new FormData(this);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response, status, xhr) {
                            if (xhr.responseURL && xhr.responseURL !== window.location.href) {
                                window.location.href = xhr.responseURL;
                                return;
                            }
                            // Cek jika response mengandung kata sukses (fallback)
                            if (typeof response === 'string' && response.toLowerCase().includes('berhasil diupdate')) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data berhasil disimpan.',
                                }).then(() => {
                                     window.location.href = '<?= base_url('dashboard/penghuni/viewpj') ?>';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menyimpan data. Silakan cek kembali.',
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                            });
                        }
                    });
                }
            });
            return false;
        });

        if (!$('#latitude').val() || !$('#longitude').val()) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    $('#latitude').val(lat.toFixed(8));
                    $('#longitude').val(lng.toFixed(8));
                    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.display_name) {
                                $('#alamat_maps').val(data.display_name);
                            }
                        });
                    if (typeof marker !== 'undefined') {
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 15);
                    }
                }, function(error) {
                });
            }
        }
    });

    function addAnggota() {
        let valid = true;
        $('#nikAnggota, #namaAnggota, #tempatLahirAnggota, #tanggalLahirAnggota, #jeniskelaminAnggota, #hubunganAnggota').each(function() {
            if (!$(this).val()) {
                showError(this, 'Wajib diisi!');
                valid = false;
            } else {
                clearError(this);
            }
        });
        if ($('#nikAnggota').val().length !== 16) {
            showError('#nikAnggota', 'NIK harus 16 digit!');
            valid = false;
        }
        if (!valid) return;

        const nik = $('#nikAnggota').val();
        const nama = $('#namaAnggota').val();
        const tempatLahir = $('#tempatLahirAnggota').val();
        const tanggalLahir = $('#tanggalLahirAnggota').val();
        const jeniskelamin = $('#jeniskelaminAnggota').val();
        const hubungan = $('#hubunganAnggota').val();
        const pekerjaan = $('#pekerjaanAnggota').val();
        const editNik = $('#modalTambahAnggota').data('edit-nik');

        if (nik && nama && tempatLahir && tanggalLahir && jeniskelamin && hubungan) {
            if (editNik) {
                const idx = window.anggotaKeluarga.findIndex(a => a.nik === editNik);
                if (idx !== -1) {
                    window.anggotaKeluarga[idx] = {
                        nik,
                        nama,
                        tempat_lahir: tempatLahir,
                        tanggal_lahir: tanggalLahir,
                        jenis_kelamin: jeniskelamin,
                        hubungan,
                        pekerjaan: pekerjaan || null
                    };
                }
                $('#modalTambahAnggota').removeData('edit-nik');
            } else {
                const exists = window.anggotaKeluarga.some(anggota => anggota.nik === nik);
                if (exists) {
                    Swal.fire('Error', 'NIK sudah ada di daftar anggota.', 'error');
                    return;
                }
                window.anggotaKeluarga.push({ 
                    nik, 
                    nama, 
                    tempat_lahir: tempatLahir, 
                    tanggal_lahir: tanggalLahir, 
                    jenis_kelamin: jeniskelamin,
                    hubungan,
                    pekerjaan: pekerjaan || null
                });
            }
            updateTable();
            $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));
            $('#nikAnggota').val('').prop('readonly', false);
            $('#namaAnggota').val('');
            $('#tempatLahirAnggota').val('');
            $('#tanggalLahirAnggota').val('');
            $('#jeniskelaminAnggota').val('');
            $('#hubunganAnggota').val('');
            $('#pekerjaanAnggota').val('');
            $('#modalTambahAnggota').modal('hide');
        } else {
            Swal.fire('Perhatian', 'Lengkapi semua data anggota keluarga yang wajib diisi (*).', 'warning');
        }
    }

    function updateTable() {
        const tbody = $('#anggotaBody');
        tbody.empty();
        if (window.anggotaKeluarga.length === 0) {
            tbody.append('<tr><td colspan="7" class="text-center">Belum ada data anggota keluarga.</td></tr>');
        } else {
            window.anggotaKeluarga.forEach((anggota, index) => {
                const row = `
                    <tr>
                        <td>${anggota.nik}</td>
                        <td>${anggota.nama}</td>
                        <td>${anggota.tempat_lahir}</td>
                        <td>${anggota.tanggal_lahir}</td>
                        <td>${anggota.jenis_kelamin}</td>
                        <td>${anggota.hubungan}</td>
                        <td>${anggota.pekerjaan}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" onclick="editAnggotaByNIK('${anggota.nik}')"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteAnggota(${index})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }
    }

    function deleteAnggota(index) {
        window.anggotaKeluarga.splice(index, 1);
        updateTable();
        $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));
    }

    function deleteAnggotaByNIK(nik) {
        window.anggotaKeluarga = window.anggotaKeluarga.filter(a => a.nik !== nik);
        updateTable();
        $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));
    }

    function editAnggotaByNIK(nik) {
        const anggota = window.anggotaKeluarga.find(a => a.nik === nik);
        if (!anggota) return;
        $('#nikAnggota').val(anggota.nik).prop('readonly', true);
        $('#namaAnggota').val(anggota.nama);
        $('#tempatLahirAnggota').val(anggota.tempat_lahir);
        $('#tanggalLahirAnggota').val(anggota.tanggal_lahir);
        $('#jeniskelaminAnggota').val(anggota.jenis_kelamin);
        $('#hubunganAnggota').val(anggota.hubungan);
        $('#pekerjaanAnggota').val(anggota.pekerjaan);
        $('#modalTambahAnggotaLabel').text('Edit Anggota Keluarga');
        $('#modalTambahAnggota').modal('show');
        $('#modalTambahAnggota').data('edit-nik', nik);
    }

    $('#modalTambahAnggota').on('hidden.bs.modal', function() {
        if (!$('#modalTambahAnggota').data('edit-nik')) {
            $('#nikAnggota').val('').prop('readonly', false);
            $('#namaAnggota').val('');
            $('#tempatLahirAnggota').val('');
            $('#tanggalLahirAnggota').val('');
            $('#jeniskelaminAnggota').val('');
            $('#hubunganAnggota').val('');
            $('#pekerjaanAnggota').val('');
        }
        $('#modalTambahAnggotaLabel').text('Tambah Anggota Keluarga');
        $('#modalTambahAnggota').removeData('edit-nik');
    });

    $(document).on('click', '[data-bs-target="#modalTambahAnggota"]', function() {
        if (!$('#modalTambahAnggota').data('edit-nik')) {
            $('#nikAnggota').val('').prop('readonly', false);
            $('#namaAnggota').val('');
            $('#tempatLahirAnggota').val('');
            $('#tanggalLahirAnggota').val('');
            $('#jeniskelaminAnggota').val('');
            $('#hubunganAnggota').val('');
            $('#pekerjaanAnggota').val('');
            $('#modalTambahAnggotaLabel').text('Tambah Anggota Keluarga');
        }
    });

    function showPreviewImage(type, previewId, modalId) {
        let imgUrl = '';
        if (type === 'kk') {
            const fileInput = document.getElementById('foto_kk');
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewKKImg').src = e.target.result;
                    $('#modalPreviewKK').modal('show');
                };
                reader.readAsDataURL(file);
            } else {
                Swal.fire('Error', 'Anda belum memasukkan gambar!', 'error');
            }
        }
    }


    let map, marker;

    async function getAddressFromCoordinates(lat, lng) {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
        const data = await res.json();
        return data.display_name || '';
    }

    async function searchLocation() {
        const address = $('#alamat_maps').val();
        if (!address) {
            Swal.fire('Perhatian', 'Masukkan alamat terlebih dahulu', 'warning');
            return;
        }

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
            const data = await res.json();

            if (data && data.length > 0) {
                const { lat, lon } = data[0];
                marker.setLatLng([lat, lon]);
                map.setView([lat, lon], 15);
                $('#latitude').val(parseFloat(lat).toFixed(8));
                $('#longitude').val(parseFloat(lon).toFixed(8));
            } else {
                Swal.fire('Error', 'Alamat tidak ditemukan', 'error');
            }
        } catch (err) {
            console.error("Error searching location:", err);
            Swal.fire('Error', 'Gagal mencari lokasi', 'error');
        }
    }

    function initMap(lat = -6.2088, lng = 106.8456) {
        const providedLat = parseFloat('<?= $pj->latitude ?? "" ?>');
        const providedLng = parseFloat('<?= $pj->longitude ?? "" ?>');

        if (!isNaN(providedLat) && !isNaN(providedLng)) {
            lat = providedLat;
            lng = providedLng;
        }

        map = L.map('map').setView([lat, lng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        map.on('click', async function(e) {
            const { lat, lng } = e.latlng;
            const address = await getAddressFromCoordinates(lat, lng);
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#alamat_maps').val(address);
            marker.setLatLng([lat, lng]);
        });

        marker.on('dragend', async function() {
            const { lat, lng } = marker.getLatLng();
            const address = await getAddressFromCoordinates(lat, lng);
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#alamat_maps').val(address);
        });
    }

    $('#search-location').on('click', searchLocation);

    function showError(input, message) {
        $(input).addClass('is-invalid');
        $(input).next('.invalid-feedback').text(message).show();
    }

    function clearError(input) {
        $(input).removeClass('is-invalid');
        $(input).next('.invalid-feedback').text('').hide();
    }
</script>