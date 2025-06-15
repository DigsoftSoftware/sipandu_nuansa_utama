<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <form action="<?= base_url('dashboard/pj/store') ?>" method="POST" enctype="multipart/form-data" id="pjForm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Buat Akun Penanggung Jawab</h4>
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
                        <img src="<?= base_url('assets/images/profile/scan_kk.png') ?>" alt="Scan KK" class="img-fluid" style="max-height: 400px; filter: grayscale(100%);">
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
                            <input type="text" name="no_kk" class="form-control" required placeholder="Masukkan No KK">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIK Kepala Keluarga (Isi Sesuai KK)</label>
                            <input type="text" name="nik" class="form-control" required placeholder="Masukkan NIK Kepala Keluarga">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Masukkan Username">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="inputPassword" class="form-control" required placeholder="Masukkan Password">
                                <span class="input-group-text cursor-pointer" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="iconPassword"></i>
                                </span>
                            </div>
                            <span class="invalid-feedback error-message"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="Masukkan Email">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kepala Keluarga (Isi Sesuai KK)</label>
                            <input type="text" name="nama_pj" class="form-control" required placeholder="Masukkan Nama Kepala Keluarga">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Handphone</label>
                            <input type="number" name="no_hp" class="form-control" required placeholder="Masukkan No Handphone">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir (Isi Sesuai KK)</label>
                            <input type="text" name="tempat_lahir" class="form-control" required placeholder="Masukkan Tempat Lahir">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select<?= isset($error) && strpos($error, 'Jenis Kelamin') !== false ? ' is-invalid' : '' ?>" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="LAKI - LAKI" <?= set_value('jenis_kelamin') == 'LAKI - LAKI' ? 'selected' : '' ?>>LAKI-LAKI</option>
                                <option value="PEREMPUAN" <?= set_value('jenis_kelamin') == 'PEREMPUAN' ? 'selected' : '' ?>>PEREMPUAN</option>
                            </select>
                            <span class="invalid-feedback error-message">
                                <?php if (isset($error) && strpos($error, 'Jenis Kelamin') !== false) echo strip_tags($error); ?>
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir (Isi Sesuai KK)</label>
                            <input type="date" name="tanggal_lahir" class="form-control" required placeholder="Pilih Tanggal Lahir">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wilayah</label>
                            <select class="form-select" name="wilayah_id" id="wilayah_id" required>
                                <option value="">-- Pilih Wilayah --</option>
                                <?php foreach ($wilayah as $w): ?>
                                    <option value="<?= $w->id ?>"><?= $w->wilayah?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Detail</label>
                            <select class="form-select" name="alamat_detail" id="alamat_detail" required>
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
                                        echo '<option value="'.$jalan.'">'.$jalan.'</option>';
                                    }
                                ?>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Rumah</label>
                            <select class="form-select" name="alamat_no" id="alamat_no" required>
                                <option value="">-- Pilih No Rumah --</option>
                                <?php for ($i = 1; $i <= 40; $i++) {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                } ?>
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
                                echo form_dropdown('status_rumah', $options, '', 'class="form-select" required');
                            ?>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="location-inputs">
                            <div class="mb-3">
                                <label class="form-label">Lokasi (Klik pada peta)</label>
                                <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly required>
                                    <span class="invalid-feedback"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly required>
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Maps</label>
                                <textarea name="alamat_maps" id="alamat_maps" class="form-control" required></textarea>
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
                                            <th>Jenis Kelamin</th>
                                            <th>Hubungan</th>
                                            <th>Pekerjaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="anggotaBody">
                                        <tr><td colspan="8" class="text-center">Belum ada data anggota keluarga.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="anggota_keluarga" id="anggota_keluarga_input">
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/pj/view') ?>'">Kembali</button>
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
                    <input type="text" id="nikAnggota" class="form-control" maxlength="17" required placeholder="Masukkan NIK Anggota">
                    <span class="invalid-feedback"></span>
                    <small class="text-muted">Masukkan 16 digit NIK sesuai KTP/KK</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="namaAnggota" class="form-control" maxlength="100" required placeholder="Masukkan Nama Lengkap">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" id="tempatLahirAnggota" class="form-control" maxlength="20" required placeholder="Masukkan Tempat Lahir">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" id="tanggalLahirAnggota" class="form-control" required placeholder="Pilih Tanggal Lahir">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jeniskelaminAnggota" class="form-select <?= form_error('jenis_kelamin') ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="LAKI - LAKI" <?= set_value('jenis_kelamin') == 'LAKI - LAKI' ? 'selected' : '' ?>>LAKI-LAKI</option>
                        <option value="PEREMPUAN" <?= set_value('jenis_kelamin') == 'PEREMPUAN' ? 'selected' : '' ?>>PEREMPUAN</option>
                    </select>
                    <span class="invalid-feedback error-message"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hubungan dengan Kepala Keluarga</label>
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
                                echo form_dropdown('hubunganAnggota', $options, set_value('hubunganAnggota'), 'class="form-select" id="hubunganAnggota" required');
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
                                echo form_dropdown('pekerjaanAnggota', $options, set_value('pekerjaanAnggota'), 'class="form-select" id="pekerjaanAnggota" required');
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
                <div class="text-center image-container">
                    <img id="previewKKImg" src="#" alt="Preview KK" class="img-fluid rounded">
    
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function() {
        window.anggotaKeluarga = [];

        $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));

        initMap();
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
                    console.error('Error mendapatkan lokasi:', error);
                });
            }
        }

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
    });

    $('#pjForm').on('submit', function(e) {
        // Pastikan field hidden selalu diisi (array kosong jika tidak ada anggota)
        $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));
        let isValid = true;
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        // Validasi semua input/select/textarea yang bukan file/image dan bukan map
        $(this).find('input:not([type="file"]):not([readonly]), select:not([readonly]), textarea:not([readonly])').each(function() {
            if ($(this).prop('required') && !$(this).val()) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').text('Field ini harus diisi');
                isValid = false;
            }
        });
        // Email validation
        const email = $('input[name="email"]');
        if (email.length && !isValidEmail(email.val())) {
            email.addClass('is-invalid');
            email.next('.invalid-feedback').text('Format email tidak valid');
            isValid = false;
        }
        // Phone number validation
        const phone = $('input[name="no_hp"]');
        if (phone.length && !isValidPhone(phone.val())) {
            phone.addClass('is-invalid');
            phone.next('.invalid-feedback').text('Nomor handphone tidak valid');
            isValid = false;
        }
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Mohon periksa kembali form isian Anda!'
            });
            return false;
        }
        // Hanya preventDefault jika validasi sukses, lalu submit manual setelah konfirmasi
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Simpan Data?',
            text: 'Pastikan data sudah benar sebelum disimpan!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // submit form secara manual
                form.submit();
            }
        });
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
        const jenis_kelamin = $('#jeniskelaminAnggota').val();
        const hubungan = $('#hubunganAnggota').val();
        const pekerjaan = $('#pekerjaanAnggota').val();

        const editNIK = $('#modalTambahAnggota').data('edit-nik');

        if (editNIK) {
            const anggota = window.anggotaKeluarga.find(a => a.nik === editNIK);
            if (anggota) {
                anggota.nik = nik;
                anggota.nama = nama;
                anggota.tempat_lahir = tempatLahir;
                anggota.tanggal_lahir = tanggalLahir;
                anggota.jenis_kelamin = jenis_kelamin;
                anggota.hubungan = hubungan;
                anggota.pekerjaan = pekerjaan || null;
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
                jenis_kelamin,
                hubungan,
                pekerjaan: pekerjaan || null
            });
        }

        updateTable(); 
        $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));

        $('#nikAnggota').val('');
        $('#namaAnggota').val('');
        $('#tempatLahirAnggota').val('');
        $('#tanggalLahirAnggota').val('');
        $('#jeniskelaminAnggota').val('');
        $('#hubunganAnggota').val('');
        $('#pekerjaanAnggota').val('');
        $('#modalTambahAnggota').modal('hide');
    }

    function updateTable() {
        const tbody = $('#anggotaBody');
        tbody.empty();

        if (window.anggotaKeluarga.length === 0) {
            tbody.append('<tr><td colspan="8" class="text-center">Belum ada data anggota keluarga.</td></tr>');
        } else {
            window.anggotaKeluarga.forEach((anggota, index) => {
                const row = `
                    <tr>
                        <td>${anggota.nik}</td>
                        <td>${anggota.nama}</td>
                        <td>${anggota.tempat_lahir}</td>
                        <td>${anggota.tanggal_lahir}</td>
                        <td>${anggota.jenis_kelamin || '-'}</td>
                        <td>${anggota.hubungan}</td>
                        <td>${anggota.pekerjaan || '-'}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" onclick="editAnggotaByNIK('${anggota.nik}')">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteAnggota(${index})">Hapus</button>
                        </td>
                    </tr>`;
                tbody.append(row);
            });
        }
    }

    function deleteAnggota(index) {
        window.anggotaKeluarga.splice(index, 1);
        updateTable();
        $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));
    }

    function editAnggotaByNIK(nik) {
        const anggota = window.anggotaKeluarga.find(a => a.nik === nik);
        if (!anggota) return;
        $('#nikAnggota').val(anggota.nik);
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
            $('#nikAnggota').val('');
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
            $('#modalTambahAnggota button.btn-primary').text('Tambah'); 
        } else {
            $('#modalTambahAnggota button.btn-primary').text('Update');
        }
    });

    function showPreviewImage(type, previewId, modalId) {
        const fileInput = document.getElementById(type === 'kk' ? 'foto_kk' : 'foto_ktp');
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewId + 'Img');
                img.src = e.target.result;
                img.style.transformOrigin = 'center';
                const container = document.querySelector('.image-container');
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();
            };
            reader.readAsDataURL(file);
        } else {
            Swal.fire('Error', 'Anda belum memasukkan gambar!', 'error');
        }
    }

    let map, marker;

    async function getAddressFromCoordinates(lat, lng) {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
        const data = await res.json();
        return data.display_name || '';
    }

    function initMap(lat = -6.2088, lng = 106.8456) {
        map = L.map('map').setView([lat, lng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        map.on('click', async function(e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            $('#latitude').val(lat.toFixed(8));
            $('#longitude').val(lng.toFixed(8));

            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat_maps').val(address);
        });

        marker.on('dragend', async function() {
            const { lat, lng } = marker.getLatLng();
            $('#latitude').val(lat.toFixed(8));
            $('#longitude').val(lng.toFixed(8));
            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat_maps').val(address);
        });
    }

    $('#search-location').on('click', async function() {
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
    });

    async function scanKK() {
        const input = document.getElementById('foto_kk');
        if (!input.files.length) {
            Swal.fire('Perhatian', 'Pilih gambar KK terlebih dahulu.', 'warning');
            return;
        }

        const file = input.files[0];
        const reader = new FileReader();

        $('#loading-ocr').show();

        reader.onload = async function() {
            const ocrResult = await Tesseract.recognize(
                reader.result,
                'ind',
                {
                    logger: () => {}
                }
            );

            const text = ocrResult.data.text;
            const kkMatch = text.match(/Nomor\s*Kartu\s*Keluarga\s*:?\s*(\d{16})/i);
            if (kkMatch) {
                $('input[name="no_kk"]').val(kkMatch[1]);
            }
            const nikMatch = text.match(/NIK\s*:?\s*(\d{16})/i);
            const namaMatch = text.match(/Nama\s*:?\s*([^\n]+)/i);
            const ujangMatch = text.toLowerCase().includes('ujang priyatna');

            if (nikMatch) {
                $('input[name="nik"]').val(nikMatch[1]);
            }
            if (namaMatch) {
                $('input[name="nama_pj"]').val(namaMatch[1].trim());
            }

            const ttlParsed = parseTempatTanggalLahir(text);
            if (ttlParsed.tempat) {
                $('input[name="tempat_lahir"]').val(ttlParsed.tempat);
            }
            if (ttlParsed.tanggal) {
                $('input[name="tanggal_lahir"]').val(ttlParsed.tanggal);
            }

            let anggotaList;
            anggotaList.forEach(anggota => {
                window.anggotaKeluarga.push(anggota);
            });
            updateTable();
            $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));

            Swal.fire('Sukses', 'Data KK berhasil dipindai dan diisi otomatis.', 'success');
            $('#loading-ocr').hide();
        };

        reader.readAsDataURL(file);
    }

    function parseTempatTanggalLahir(text) {
        const ttlRegex = /(Tempat\s*\/?\s*Tgl\s*Lahir|TTL)\s*[:\-]?\s*(.+)/i;
        const match = text.match(ttlRegex);
        if (!match) return {
            tempat: '',
            tanggal: ''
        };

        let ttlLine = match[2].trim();
        let tempat = '',
            tanggal = '';

        if (ttlLine.includes(',')) {
            const parts = ttlLine.split(',');
            tempat = parts[0].trim();
            tanggal = formatTanggal(parts[1]);
        } else {
            const dateRegex = /(\d{1,2})[^\d]?(\d{1,2})[^\d]?(\d{4})/;
            const dateMatch = ttlLine.match(dateRegex);
            if (dateMatch) {
                tempat = ttlLine.replace(dateRegex, '').trim();
                tanggal = `${dateMatch[3]}-${dateMatch[2].padStart(2, '0')}-${dateMatch[1].padStart(2, '0')}`;
            }
        }

        return {
            tempat,
            tanggal
        };
    }

    function parseAnggotaKeluargaFromTable(text) {
        const anggotaList = [];
        const lines = text.split('\n');
        
        for (let i = 0; i < lines.length; i++) {
            const line = lines[i].trim();
            
            const nikMatch = line.match(/(\d{16})/);
            if (nikMatch) {
                const nik = nikMatch[1];
                const nama = lines[i+1] ? lines[i+1].trim() : '';
                const ttl = lines[i+2] ? parseTempatTanggalLahir(lines[i+2]) : {tempat: '', tanggal: ''};
                const hubungan = lines[i+3] ? lines[i+3].trim() : '';
                const pekerjaan = lines[i+4] ? lines[i+4].trim() : '';
                
                if (nik && nama && (ttl.tempat || ttl.tanggal)) {
                    anggotaList.push({
                        nik: nik,
                        nama: nama,
                        tempat_lahir: ttl.tempat,
                        tanggal_lahir: ttl.tanggal,
                        hubungan: hubungan || 'Anggota Keluarga',
                        pekerjaan: pekerjaan || '-'
                    });
                }
            }
        }
        
        return anggotaList;
    }

    function formatTanggal(tanggalStr) {
        const dateRegex = /(\d{1,2})[^\d]?(\d{1,2})[^\d]?(\d{4})/;
        const match = tanggalStr.match(dateRegex);
        if (match) {
            return `${match[3]}-${match[2].padStart(2, '0')}-${match[1].padStart(2, '0')}`;
        }
        return '';
    }

    function showError(input, message) {
        $(input).addClass('is-invalid');
        $(input).next('.invalid-feedback').text(message).show();
    }
    function clearError(input) {
        $(input).removeClass('is-invalid');
        $(input).next('.invalid-feedback').text('').hide();
    }

    // Real-time validation (kecuali file/image dan maps)
    $('input[required]:not([type="file"]):not([readonly]):not([name="latitude"]):not([name="longitude"]):not([id="latitude"]):not([id="longitude"]), select[required]:not([readonly]), textarea[required]:not([id="alamat_maps"])').on('change keyup', function() {
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        } else {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('Field ini harus diisi');
        }
    });
    $('input[name="email"]').on('change keyup', function() {
        if (isValidEmail($(this).val())) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        } else {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('Format email tidak valid');
        }
    });
    $('input[name="no_hp"]').on('change keyup', function() {
        if (isValidPhone($(this).val())) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        } else {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('Nomor handphone tidak valid');
        }
    });

    // Validasi modal tambah anggota
    $('#nikAnggota, #namaAnggota, #tempatLahirAnggota, #tanggalLahirAnggota, #jeniskelaminAnggota, #hubunganAnggota').on('input change', function() {
        if (!$(this).val()) {
            showError(this, 'Wajib diisi!');
        } else {
            clearError(this);
        }
    });
    $('#nikAnggota').on('input', function() {
        if ($(this).val().length !== 16) {
            showError(this, 'NIK harus 16 digit!');
        } else {
            clearError(this);
        }
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^[0-9]{10,13}$/;
        return phoneRegex.test(phone);
    }
</script>