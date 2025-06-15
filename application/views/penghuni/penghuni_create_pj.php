<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?= $error ?>
    </div>
<?php endif; ?>


<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('dashboard/penghuni/store_pj') ?>" method="POST" enctype="multipart/form-data">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Tambah Data Pendatang</h4>
                </div>
                <div class="row">
                    <!-- ALERT PERHATIAN -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                                <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                    <li>Pastikan Anda mengunggah <strong>Foto</strong> dan <strong>Scan KTP</strong> dengan jelas.</li>
                                    <li>Data seperti <em>NIK</em>, <em>Nama</em>, <em>Tempat/Tanggal Lahir</em>, dan lainnya harus diisi manual sesuai KTP.</li>
                                    <li>Jangan lupa untuk <strong>klik lokasi</strong> Anda pada peta untuk mengisi koordinat.</li>
                                    <li><strong>Alamat Sekarang</strong> akan terisi otomatis berdasarkan GPS browser Anda.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FOTO & SCAN KTP -->
                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <label class="form-label">Foto</label><br>
                            <img src="<?= base_url('assets/images/profile/foto.png') ?>" alt="Foto" class="img-fluid" style="max-height: 200px; filter: grayscale(100%);">
                            <div class="mt-4">
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*" required>
                                <button type="button" class="btn btn-primary mt-4 text-center" onclick="showPreviewImage('foto', 'previewFotoImg', 'modalPreviewFoto')">Preview Foto</button>
                            </div>
                        </div>
                        <div class="col-md-6  text-center">
                            <label class="form-label">KTP (Kartu Tanda Penduduk)</label><br>
                            <img src="<?= base_url('assets/images/profile/scan_ktp.png') ?>" alt="Scan KTP" class="img-fluid" style="max-height: 200px; filter: grayscale(100%);">
                            <div class="mt-4">
                                <input type="file" name="ktp" id="ktp" class="form-control" accept="image/*,.pdf" required>
                            </div>
                            <button type="button" class="btn btn-primary mt-4 text-center" onclick="showPreviewImage('ktp', 'previewKtpImg', 'modalPreviewKTP')">Preview KTP</button>
                            <button type="button" class="btn btn-success mt-4 text-center" onclick="scanKTP()">Scan & Isi Otomatis</button>
                            <div id="loading-ocr" class="text-muted mt-2" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Memproses gambar...
                            </div>
                        </div>
                    </div>

                    <!-- Modal Preview Foto -->
                    <div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-labelledby="modalPreviewFotoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPreviewFotoLabel">Preview Foto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="previewFotoImg" src="#" alt="Preview Foto" class="img-fluid rounded shadow">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Preview KTP -->
                    <div class="modal fade" id="modalPreviewKTP" tabindex="-1" aria-labelledby="modalPreviewKTPLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPreviewKTPLabel">Preview KTP</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="previewKtpImg" src="#" alt="Preview KTP" class="img-fluid rounded shadow">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KIRI -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">NIK (Isi Sesuai KTP)</label>
                            <input type="text" name="nik" class="form-control" required placeholder="Contoh: 1234567890123456">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap (Isi Sesuai KTP)</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Contoh: Budi Santoso">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Handphone</label>
                            <input type="number" name="no_hp" class="form-control" placeholder="Contoh: 081234567890">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir (Isi Sesuai KTP)</label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Contoh: DENPASAR">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir (Isi Sesuai KTP)</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin (Isi Sesuai KTP)</label>
                            <select name="jk" class="form-select" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="LAKI - LAKI">LAKI-LAKI</option>
                                <option value="PEREMPUAN">PEREMPUAN</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-select">
                                <option value="">Pilih Agama</option>
                                <option value="ISLAM">ISLAM</option>
                                <option value="KRISTEN">KRISTEN</option>
                                <option value="KATOLIK">KATOLIK</option>
                                <option value="HINDU">HINDU</option>
                                <option value="BUDDHA">BUDDHA</option>
                                <option value="KONGHUCU">KONGHUCU</option>
                            </select>
                        </div>

                        <!-- Alamat asal -->
                        <div class="mb-3">
                            <label class="form-label">Provinsi Asal (Isi Sesuai KTP)</label>
                            <select id="provinsi" name="provinsi_asal" class="form-select" required></select>
                            <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kabupaten/Kota Asal (Isi Sesuai KTP)</label>
                            <select id="kabupaten" name="kabupaten_asal" class="form-select" required></select>
                            <input type="hidden" name="kabupaten_nama" id="kabupaten_nama">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan Asal (Isi Sesuai KTP)</label>
                            <select id="kecamatan" name="kecamatan_asal" class="form-select" required></select>
                            <input type="hidden" name="kecamatan_nama" id="kecamatan_nama">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelurahan Asal (Isi Sesuai KTP)</label>
                            <select id="kelurahan" name="kelurahan_asal" class="form-select" required></select>
                            <input type="hidden" name="kelurahan_nama" id="kelurahan_nama">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RT</label>
                                <input type="text" name="rt" class="form-control" placeholder="Contoh: 001">
                            </div>
    
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RW</label>
                                <input type="text" name="rw" class="form-control" placeholder="Contoh: 002">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Asal (Isi Sesuai KTP)</label>
                            <textarea name="alamat_asal" class="form-control" placeholder="Contoh : Jalan Malioboro No.6, Denpasar" required></textarea>
                        </div>

                    </div>

                    <!-- KOLOM KANAN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="usePJLocation">
                                <label class="form-check-label" for="usePJLocation">Gunakan alamat dan lokasi yang sama dengan Penanggung Jawab</label>
                            </div>
                        </div>

                        <div class="location-inputs">
                            <div class="mb-3">
                                <label class="form-label">Lokasi (Klik pada peta)</label>
                                <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                                </div>
    
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Sekarang</label>
                                <textarea name="alamat_sekarang" id="alamat_sekarang" class="form-control" required></textarea>
                                <button type="button" id="search-location" class="btn btn-warning mt-2 w-100"> <i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi</button>
                            </div>

                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tujuan</label>
                            <select id="tujuan-select" name="tujuan_pilihan" class="form-select" required>
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
                            <input type="date" name="tanggal_masuk" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="kaling_id" class="form-label">Kepala Lingkungan</label>
                            <select name="kaling_id" id="kaling_id" class="form-select" required>
                                <option value="">Pilih Kepala Lingkungan</option>
                                <?php foreach ($kaling as $k): ?>
                                    <option value="<?= $k->id ?>"><?= $k->nama ?> - <?= $k->wilayah ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="wilayah_id" class="form-label">Wilayah</label>
                            <select name="wilayah_id" id="wilayah_id" class="form-select" required>
                                <option value="">Pilih Wilayah</option>
                                <?php foreach ($wilayah as $w): ?>
                                    <option value="<?= $w->id ?>"><?= $w->wilayah ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('dashboard/penghuni/viewpj') ?>" class="btn btn-danger">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<style>
    .error-message {
        color: #dc3545;
        font-size: 80%;
        margin-top: 0.25rem;
    }
    .form-control.error {
        border-color: #dc3545;
    }
    .form-control.valid {
        border-color: #198754;
    }
    .validation-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
    }
    .form-group {
        position: relative;
    }
</style>

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
    
    function showPreviewImage(inputId, imgPreviewId, modalId) {
        const input = document.getElementById(inputId);
        if (!input || !input.files || !input.files[0]) {
            Swal.fire({
                icon: 'warning',
                title: 'File tidak ditemukan',
                text: 'Pilih gambar terlebih dahulu.'
            });
            return;
        }

        const file = input.files[0];

        if (!file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'error',
                title: 'File bukan gambar',
                text: 'Hanya file gambar yang bisa dipreview.'
            });
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById(imgPreviewId);
            previewImg.src = e.target.result;

            const modalEl = document.getElementById(modalId);
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        };
        reader.readAsDataURL(file);
    }


    async function scanKTP() {
        const input = document.getElementById('ktp');
        if (!input.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Gambar KTP belum dipilih',
                text: 'Pilih gambar KTP dulu.'
            });
            return;
        }


        const file = input.files[0];
        const reader = new FileReader();

        $('#loading-ocr').show();

        reader.onload = async function() {
            const ocrResult = await Tesseract.recognize(
                reader.result,
                'ind', {
                    logger: () => {}
                }
            );

            const text = ocrResult.data.text;

            const nik = text.match(/NIK\s*:?\s*(\d{16})/i);
            const nama = text.match(/Nama\s*:?\s*(.+)/i);
            const jk = text.match(/Jenis Kelamin\s*:?\s*(\w+)/i);
            const ttlParsed = parseTempatTanggalLahir(text);

            if (nik) $('input[name="nik"]').val(nik[1]);
            if (nama) $('input[name="nama"]').val(nama[1].trim());
            if (ttlParsed.tempat) $('input[name="tempat_lahir"]').val(ttlParsed.tempat);
            if (ttlParsed.tanggal) $('input[name="tanggal_lahir"]').val(ttlParsed.tanggal);
            if (jk) $('select[name="jk"]').val(jk[1].toUpperCase());

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

    function formatTanggal(input) {
        const parts = input.replace(/[^\d]/g, ' ').trim().split(/\s+/);
        if (parts.length >= 3) {
            let [day, month, year] = parts;
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        }
        return '';
    }

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

    function initMap(lat = -6.2088, lng = 106.8456) {
        map = L.map('map').setView([lat, lng], 12);
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
        $.getJSON("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function(data) {
            $('#provinsi').html('<option value="">Pilih Provinsi</option>');
            $.each(data, function(i, p) {
                $('#provinsi').append(`<option value="${p.name}">${p.name}</option>`);
            });
        });

        $('#provinsi').change(function() {
            const selectedProvName = $(this).val();
            $.getJSON("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function(provinces) {
                const province = provinces.find(p => p.name === selectedProvName);
                if (province) {
                    $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${province.id}.json`, function(data) {
                        $('#kabupaten').html('<option value="">Pilih Kabupaten</option>');
                        $.each(data, function(i, k) {
                            $('#kabupaten').append(`<option value="${k.name}" data-id="${k.id}">${k.name}</option>`);
                        });
                    });
                }
            });
        });

        $('#kabupaten').change(function() {
            const selectedKabId = $(this).find(':selected').data('id');
            if (selectedKabId) {
                $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${selectedKabId}.json`, function(data) {
                    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                    $.each(data, function(i, kc) {
                        $('#kecamatan').append(`<option value="${kc.name}" data-id="${kc.id}">${kc.name}</option>`);
                    });
                });
            }
        });

        $('#kecamatan').change(function() {
            const selectedKecId = $(this).find(':selected').data('id');
            if (selectedKecId) {
                $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${selectedKecId}.json`, function(data) {
                    $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
                    $.each(data, function(i, kl) {
                        $('#kelurahan').append(`<option value="${kl.name}">${kl.name}</option>`);
                    });
                });
            }
        });

        $('#provinsi').change(function() {
            $('#provinsi_nama').val($(this).val());
        });
        $('#kabupaten').change(function() {
            $('#kabupaten_nama').val($(this).val());
        });
        $('#kecamatan').change(function() {
            $('#kecamatan_nama').val($(this).val());
        });
        $('#kelurahan').change(function() {
            $('#kelurahan_nama').val($(this).val());
        });
    }

    function getCurrentLocationAndAddress() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                $('#latitude').val(lat);
                $('#longitude').val(lng);
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 15);

                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                const data = await res.json();
                $('#alamat_sekarang').val(data.display_name || '');
            });
        }
    }

    $(document).ready(function() {
        initMap();
        loadWilayah();
        getCurrentLocationAndAddress();

        // Form validation
        $('form').on('submit', function(e) {
            let isValid = true;
            
            // Reset previous validations
            $('.error-message').remove();
            $('.form-control').removeClass('error valid');
            $('.validation-icon').remove();

            // Validate NIK
            const nik = $('input[name="nik"]');
            if (nik.val().length !== 16) {
                isValid = false;
                showError(nik, 'NIK harus 16 digit');
            } else {
                showSuccess(nik);
            }

            // Validate required fields (skip file/image & maps)
            $('input[required], select[required], textarea[required]').each(function() {
                if (
                    $(this).attr('type') === 'file' ||
                    $(this).attr('name') === 'latitude' || $(this).attr('name') === 'longitude' ||
                    $(this).attr('id') === 'latitude' || $(this).attr('id') === 'longitude' ||
                    $(this).attr('id') === 'map' || $(this).attr('id') === 'alamat_sekarang'
                ) return;
                if (!$(this).val()) {
                    isValid = false;
                    showError($(this), 'Field ini wajib diisi');
                } else {
                    showSuccess($(this));
                }
            });

            // Validate phone number
            const phone = $('input[name="no_hp"]');
            if (phone.val() && !isValidPhone(phone.val())) {
                isValid = false;
                showError(phone, 'Format nomor telepon tidak valid');
            } else if (phone.val()) {
                showSuccess(phone);
            }

            // Validate location
            if (!$('#latitude').val() || !$('#longitude').val()) {
                isValid = false;
                showError($('#alamat_sekarang'), 'Pilih lokasi pada peta');
            }

            if (!isValid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.error').first().offset().top - 100
                }, 500);
            }
        });

        // Real-time validation (kecuali file/image dan maps)
        $('input[required], select[required], textarea[required]').on('blur', function() {
            if (
                $(this).attr('type') === 'file' ||
                $(this).attr('name') === 'latitude' || $(this).attr('name') === 'longitude' ||
                $(this).attr('id') === 'latitude' || $(this).attr('id') === 'longitude' ||
                $(this).attr('id') === 'map' || $(this).attr('id') === 'alamat_sekarang'
            ) return;
            if (!$(this).val()) {
                showError($(this), 'Field ini wajib diisi');
            } else {
                showSuccess($(this));
            }
        });

        $('#usePJLocation').change(function() {
            if ($(this).is(':checked')) {
                $.ajax({
                    url: '<?= base_url('PJController/getPJLocation') ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            const { latitude, longitude, alamat } = response.data;
                            if (latitude && longitude && alamat) {
                                marker.setLatLng([latitude, longitude]);
                                map.setView([latitude, longitude], 15);
                                $('#latitude').val(latitude);
                                $('#longitude').val(longitude);
                                $('#alamat_sekarang').val(alamat);
                                
                                $('.location-inputs input, .location-inputs textarea').prop('readonly', true);
                                map.dragging.disable();
                                map.touchZoom.disable();
                                map.doubleClickZoom.disable();
                                map.scrollWheelZoom.disable();
                                map.boxZoom.disable();
                                map.keyboard.disable();
                                marker.dragging.disable();
                            } else {
                                alert('Data lokasi Penanggung Jawab tidak lengkap');
                                $(this).prop('checked', false);
                            }
                        } else {
                            alert(response.message || 'Gagal mengambil lokasi Penanggung Jawab');
                            $(this).prop('checked', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil lokasi Penanggung Jawab');
                        $(this).prop('checked', false);
                    }
                });
            } else {
                $('.location-inputs input, .location-inputs textarea').prop('readonly', false);
                map.dragging.enable();
                map.touchZoom.enable();
                map.doubleClickZoom.enable();
                map.scrollWheelZoom.enable();
                map.boxZoom.enable();
                map.keyboard.enable();
                marker.dragging.enable();

                getCurrentLocationAndAddress();
            }
        });
    });

    function showError(element, message) {
        element.removeClass('valid').addClass('error');
        if (!element.next('.error-message').length) {
            element.after(`<span class="error-message">${message}</span>`);
        }
    }

    function showSuccess(element) {
        element.removeClass('error').addClass('valid');
        element.next('.error-message').remove();
        element.parent().find('.validation-icon').remove();
        element.parent().append('<span class="validation-icon text-success"><i class="fas fa-check-circle"></i></span>');
    }

    function isValidPhone(phone) {
        return /^(\+62|62|0)[0-9]{9,12}$/.test(phone);
    }

    $('#search-location').on('click', async function() {
        const address = $('#alamat_sekarang').val();
        if (!address) {
            showError($('#alamat_sekarang'), 'Masukkan alamat terlebih dahulu');
            return;
        }

        const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
        const data = await res.json();

        if (data && data.length > 0) {
            const { lat, lon } = data[0];
            marker.setLatLng([lat, lon]);
            map.setView([lat, lon], 15);
            $('#latitude').val(parseFloat(lat).toFixed(8));
            $('#longitude').val(parseFloat(lon).toFixed(8));
            showSuccess($('#alamat_sekarang'));
        } else {
            showError($('#alamat_sekarang'), 'Alamat tidak ditemukan');
        }
    });
</script>