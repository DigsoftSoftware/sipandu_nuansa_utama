<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Edit Data Pendatang</h4>
            </div>

            <?php if ($penghuni->alasan): ?>
                <div class="alert alert-danger mb-4">
                    <h5 class="mb-2"><i class="fas fa-exclamation-circle me-2"></i>Alasan Penolakan:</h5>
                    <p class="mb-0"><?= $penghuni->alasan ?></p>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('dashboard/penghuni/update_pj/' . $penghuni->uuid) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <label class="form-label">Foto</label><br>
                            <?php if ($penghuni->foto_profil): ?>
                                <img src="<?= base_url('uploads/penghuni/' . $penghuni->foto_profil) ?>" alt="Foto" class="img-fluid" style="max-height: 200px;">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/profile/foto.png') ?>" alt="Foto" class="img-fluid" style="max-height: 200px; filter: grayscale(100%);">
                            <?php endif; ?>
                            <div class="mt-4">
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <label class="form-label">KTP (Kartu Tanda Penduduk)</label><br>
                            <?php if ($penghuni->foto_ktp): ?>
                                <img src="<?= base_url('uploads/penghuni/' . $penghuni->foto_ktp) ?>" alt="KTP" class="img-fluid" style="max-height: 200px;">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/profile/scan_ktp.png') ?>" alt="Scan KTP" class="img-fluid" style="max-height: 200px; filter: grayscale(100%);">
                            <?php endif; ?>
                            <div class="mt-4">
                                <input type="file" name="ktp" id="ktp" class="form-control" accept="image/*,.pdf">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah KTP</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK (Isi Sesuai KTP)</label>
                            <input type="text" name="nik" class="form-control" id="nik" value="<?= $penghuni->nik ?>" maxlength="16" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap (Isi Sesuai KTP)</label>
                            <input type="text" name="nama" class="form-control" id="nama" value="<?= $penghuni->nama_lengkap ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Handphone</label>
                            <input type="number" name="no_hp" id="no_hp" class="form-control" value="<?= $penghuni->no_hp ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir (Isi Sesuai KTP)</label>
                            <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir" value="<?= $penghuni->tempat_lahir ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir (Isi Sesuai KTP)</label>
                            <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir"
                                value="<?= $penghuni->tanggal_lahir && $penghuni->tanggal_lahir != '0000-00-00' ? date('Y-m-d', strtotime($penghuni->tanggal_lahir)) : '' ?>" required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin (Isi Sesuai KTP)</label>
                            <select name="jk" class="form-select">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="LAKI - LAKI" <?= $penghuni->jenis_kelamin == 'LAKI - LAKI' ? 'selected' : '' ?>>LAKI-LAKI</option>
                                <option value="PEREMPUAN" <?= $penghuni->jenis_kelamin == 'PEREMPUAN' ? 'selected' : '' ?>>PEREMPUAN</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select">
                                <option value="">Pilih Golongan Darah</option>
                                <?php foreach (['A', 'B', 'AB', 'O'] as $goldar): ?>
                                    <option value="<?= $goldar ?>" <?= $penghuni->golongan_darah == $goldar ? 'selected' : '' ?>><?= $goldar ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-select">
                                <option value="">Pilih Agama</option>
                                <?php
                                $agama_list = ['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA', 'KONGHUCU'];
                                foreach ($agama_list as $agama):
                                ?>
                                    <option value="<?= $agama ?>" <?= $penghuni->agama == $agama ? 'selected' : '' ?>><?= $agama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <input type="hidden" name="provinsi_nama" id="provinsi_nama" value="<?= $penghuni->provinsi_asal ?>">
                        <input type="hidden" name="kabupaten_nama" id="kabupaten_nama" value="<?= $penghuni->kabupaten_asal ?>">
                        <input type="hidden" name="kecamatan_nama" id="kecamatan_nama" value="<?= $penghuni->kecamatan_asal ?>">
                        <input type="hidden" name="kelurahan_nama" id="kelurahan_nama" value="<?= $penghuni->kelurahan_asal ?>">

                        <!-- Alamat asal -->
                        <div class="mb-3">
                            <label class="form-label">Provinsi Asal (Isi Sesuai KTP)</label>
                            <select id="provinsi" name="provinsi_asal" class="form-select"></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kabupaten/Kota Asal (Isi Sesuai KTP)</label>
                            <select id="kabupaten" name="kabupaten_asal" class="form-select"></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan Asal (Isi Sesuai KTP)</label>
                            <select id="kecamatan" name="kecamatan_asal" class="form-select"></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelurahan Asal (Isi Sesuai KTP)</label>
                            <select id="kelurahan" name="kelurahan_asal" class="form-select"></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RT</label>
                            <input type="text" name="rt" class="form-control" value="<?= $penghuni->rt ?>" placeholder="Contoh: 001">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RW</label>
                            <input type="text" name="rw" class="form-control" value="<?= $penghuni->rw ?>" placeholder="Contoh: 002">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Asal (Isi Sesuai KTP)</label>
                            <textarea name="alamat_asal" class="form-control"><?= $penghuni->alamat_asal ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lokasi (Klik pada peta)</label>
                            <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Sekarang</label>
                            <textarea name="alamat_sekarang" id="alamat_sekarang" class="form-control"><?= $penghuni->alamat_sekarang ?></textarea>
                            <button type="button" id="search-location" class="btn btn-secondary mt-2">Cari Lokasi</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" value="<?= $penghuni->latitude ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" value="<?= $penghuni->longitude ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tujuan</label>
                            <select id="tujuan-select" name="tujuan" class="form-select" required>
                                <option value="">-- Pilih Tujuan --</option>
                                <option value="Bekerja">Bekerja</option>
                                <option value="Kuliah">Kuliah</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3" id="tujuan-lainnya" style="display: none;">
                            <label class="form-label">Tuliskan Tujuan Lainnya</label>
                            <textarea id="tujuan-lainnya-text" class="form-control" placeholder="Tulis tujuan lainnya..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control" value="<?= $penghuni->tanggal_masuk && $penghuni->tanggal_masuk != '0000-00-00' ? date('Y-m-d', strtotime($penghuni->tanggal_masuk)) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="wilayah_id" class="form-label">Wilayah</label>
                            <select name="wilayah_id" id="wilayah_id" class="form-select" required>
                                <option value="">Pilih Wilayah</option>
                                <?php foreach ($wilayah as $w): ?>
                                    <option value="<?= $w->id ?>" <?= $penghuni->wilayah_id == $w->id ? 'selected' : '' ?>>
                                        <?= $w->wilayah ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kaling_id" class="form-label">Kepala Lingkungan</label>
                            <select name="kaling_id" id="kaling_id" class="form-select">
                                <option value="">Pilih Kepala Lingkungan</option>
                                <?php foreach ($kaling as $k): ?>
                                    <option value="<?= $k->id ?>" <?= $penghuni->kaling_id == $k->id ? 'selected' : '' ?>><?= $k->nama ?> - <?= $k->wilayah ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <?php if ($penghuni->status_penghuni === 'Aktif'): ?>
                        <a href="<?= base_url('dashboard/penghuni/ubah_status/' . $penghuni->uuid) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Apakah Anda yakin ingin menonaktifkan penghuni ini?')">
                            <i class="fas fa-user-times"></i> Nonaktifkan
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('dashboard/penghuni/viewpj') ?>" class="btn btn-light">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>

<script>
    const select = document.getElementById('tujuan-select');
    const lainnyaDiv = document.getElementById('tujuan-lainnya');
    const lainnyaText = document.getElementById('tujuan-lainnya-text');
    const tujuanHidden = document.getElementById('tujuan-hidden');

    select.addEventListener('change', function () {
        if (this.value === 'Lainnya') {
            lainnyaDiv.style.display = 'block';
            tujuanHidden.value = lainnyaText.value;
        } else {
            lainnyaDiv.style.display = 'none';
            tujuanHidden.value = this.value;
        }
    });

    lainnyaText.addEventListener('input', function () {
        if (select.value === 'Lainnya') {
            tujuanHidden.value = this.value;
        }
    });
    
    let map, marker;

    async function getAddressFromCoordinates(lat, lng) {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
        const data = await res.json();
        return data.display_name || '';
    }

    async function searchLocation() {
        const address = $('#alamat_sekarang').val();
        if (!address) {
            alert('Masukkan alamat terlebih dahulu');
            return;
        }

        const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
        const data = await res.json();

        if (data && data.length > 0) {
            const {
                lat,
                lon
            } = data[0];
            marker.setLatLng([lat, lon]);
            map.setView([lat, lon], 15);
            $('#latitude').val(lat);
            $('#longitude').val(lon);
            $('#alamat_sekarang').val(data[0].display_name);
        } else {
            alert('Alamat tidak ditemukan');
        }
    }

    function initMap() {
        const lat = <?= $penghuni->latitude ?? '-6.2088' ?>;
        const lng = <?= $penghuni->longitude ?? '106.8456' ?>;

        map = L.map('map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        map.on('click', async function(e) {
            const {
                lat,
                lng
            } = e.latlng;
            marker.setLatLng([lat, lng]);
            $('#latitude').val(lat);
            $('#longitude').val(lng);

            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat_sekarang').val(address);
        });

        marker.on('dragend', async function() {
            const {
                lat,
                lng
            } = marker.getLatLng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat_sekarang').val(address);
        });
    }

    function loadWilayah() {
        const provinsiSelected = '<?= $penghuni->provinsi_asal ?>';
        const kabupatenSelected = '<?= $penghuni->kabupaten_asal ?>';
        const kecamatanSelected = '<?= $penghuni->kecamatan_asal ?>';
        const kelurahanSelected = '<?= $penghuni->kelurahan_asal ?>';

        $.getJSON("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function(data) {
            $('#provinsi').html('<option value="">Pilih Provinsi</option>');
            $.each(data, function(i, p) {
                const selected = p.name === provinsiSelected ? 'selected' : '';
                $('#provinsi').append(`<option value="${p.name}" ${selected}>${p.name}</option>`);
            });
            if (provinsiSelected) $('#provinsi').trigger('change');
        });

        $('#provinsi').change(function() {
            const selectedProvName = $(this).val();
            $('#provinsi_nama').val(selectedProvName);

            $.getJSON("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function(provinces) {
                const province = provinces.find(p => p.name === selectedProvName);
                if (province) {
                    $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${province.id}.json`, function(data) {
                        $('#kabupaten').html('<option value="">Pilih Kabupaten</option>');
                        $.each(data, function(i, k) {
                            const selected = k.name === kabupatenSelected ? 'selected' : '';
                            $('#kabupaten').append(`<option value="${k.name}" data-id="${k.id}" ${selected}>${k.name}</option>`);
                        });
                        if (kabupatenSelected) $('#kabupaten').trigger('change');
                    });
                }
            });
        });

        $('#kabupaten').change(function() {
            $('#kabupaten_nama').val($(this).val());
            const selectedKabId = $(this).find(':selected').data('id');
            if (selectedKabId) {
                $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${selectedKabId}.json`, function(data) {
                    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                    $.each(data, function(i, kc) {
                        const selected = kc.name === kecamatanSelected ? 'selected' : '';
                        $('#kecamatan').append(`<option value="${kc.name}" data-id="${kc.id}" ${selected}>${kc.name}</option>`);
                    });
                    if (kecamatanSelected) $('#kecamatan').trigger('change');
                });
            }
        });

        $('#kecamatan').change(function() {
            $('#kecamatan_nama').val($(this).val());
            const selectedKecId = $(this).find(':selected').data('id');
            if (selectedKecId) {
                $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${selectedKecId}.json`, function(data) {
                    $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
                    $.each(data, function(i, kl) {
                        const selected = kl.name === kelurahanSelected ? 'selected' : '';
                        $('#kelurahan').append(`<option value="${kl.name}" ${selected}>${kl.name}</option>`);
                    });
                });
            }
        });

        $('#kelurahan').change(function() {
            $('#kelurahan_nama').val($(this).val());
        });
    }

    $(document).ready(function() {
        <?php if ($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= $this->session->flashdata('error') ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $this->session->flashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>


        initMap();
        loadWilayah();
    });

    $('#search-location').on('click', async function() {
        const address = $('#alamat').val();
        if (!address) {
            alert('Masukkan alamat terlebih dahulu');
            return;
        }

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
            const data = await res.json();

            if (data && data.length > 0) {
                const {
                    lat,
                    lon
                } = data[0];
                marker.setLatLng([lat, lon]);
                map.setView([lat, lon], 15);
                $('#latitude').val(parseFloat(lat).toFixed(8));
                $('#longitude').val(parseFloat(lon).toFixed(8));
            } else {
                alert('Alamat tidak ditemukan');
            }
        } catch (err) {
            console.error("Error searching location:", err);
            alert('Gagal mencari lokasi');
        }
    });

    // Validasi client-side untuk semua field non-file/image dan non-map
    $('form').on('submit', function(e) {
        let isValid = true;
        // Reset feedback
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        // Validasi semua input/select/textarea yang bukan file/image dan bukan map
        $(this).find('input, select, textarea').each(function() {
            // skip file/image dan maps
            if (
                $(this).attr('type') === 'file' ||
                $(this).attr('name') === 'latitude' || $(this).attr('name') === 'longitude' ||
                $(this).attr('id') === 'latitude' || $(this).attr('id') === 'longitude' ||
                $(this).attr('id') === 'map' || $(this).attr('id') === 'alamat_sekarang'
            ) return;
            if ($(this).prop('required') && !$(this).val()) {
                $(this).addClass('is-invalid');
                if ($(this).next('.invalid-feedback').length === 0) {
                    $(this).after('<span class="invalid-feedback">Field ini harus diisi</span>');
                } else {
                    $(this).next('.invalid-feedback').text('Field ini harus diisi');
                }
                isValid = false;
            }
        });
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Mohon periksa kembali form isian Anda!'
            });
            return false;
        }
    });

    // Real-time validation (kecuali file/image dan maps)
    $('input[required], select[required], textarea[required]').on('change keyup', function() {
        if (
            $(this).attr('type') === 'file' ||
            $(this).attr('name') === 'latitude' || $(this).attr('name') === 'longitude' ||
            $(this).attr('id') === 'latitude' || $(this).attr('id') === 'longitude' ||
            $(this).attr('id') === 'map' || $(this).attr('id') === 'alamat_sekarang'
        ) return;
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        } else {
            $(this).addClass('is-invalid');
            if ($(this).next('.invalid-feedback').length === 0) {
                $(this).after('<span class="invalid-feedback">Field ini harus diisi</span>');
            } else {
                $(this).next('.invalid-feedback').text('Field ini harus diisi');
            }
        }
    });
</script>