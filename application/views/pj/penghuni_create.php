<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            <form action="<?= base_url('dashboard/penghuni/store') ?>" method="POST" enctype="multipart/form-data">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Tambah Data Penghuni</h4>
                <div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('dashboard/penghuni/viewpj') ?>" class="btn btn-secondary">Kembali</a>
                </div>
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
                                    <li>Data akan diverifikasi oleh <strong>Kepala Lingkungan</strong>.</li>
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
                            <input type="text" name="nik" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap (Isi Sesuai KTP)</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir (Isi Sesuai KTP)</label>
                            <input type="text" name="tempat_lahir" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir (Isi Sesuai KTP)</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin (Isi Sesuai KTP)</label>
                            <input type="text" name="jk" class="form-control">
                        </div>

                        <!-- Alamat asal -->
                        <div class="mb-3">
                            <label class="form-label">Provinsi Asal (Isi Sesuai KTP)</label>
                            <select id="provinsi" name="provinsi_asal" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kabupaten/Kota Asal (Isi Sesuai KTP)</label>
                            <select id="kabupaten" name="kabupaten_asal" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan Asal (Isi Sesuai KTP)</label>
                            <select id="kecamatan" name="kecamatan_asal" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelurahan Asal (Isi Sesuai KTP)</label>
                            <select id="kelurahan" name="kelurahan_asal" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Asal</label>
                            <textarea name="alamat_asal" class="form-control" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Sekarang</label>
                            <textarea name="alamat_sekarang" id="alamat_sekarang" class="form-control" required></textarea>
                        </div>
                    </div>
                    
                    <!-- KOLOM KANAN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lokasi (Klik pada peta)</label>
                            <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                        </div>
                        

                        <div class="mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tujuan</label>
                            <textarea name="tujuan" class="form-control" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Keluar</label>
                            <input type="date" name="tanggal_keluar" class="form-control" required>
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

                
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>

<script>
    function showPreviewImage(inputId, imgPreviewId, modalId) {
        const input = $('#' + inputId)[0];
        if (!input.files.length) {
            alert('Pilih gambar terlebih dahulu.');
            return;
        }

        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#' + imgPreviewId).attr('src', e.target.result);
            const modal = new bootstrap.Modal($('#' + modalId)[0]);
            modal.show();
        };
        reader.readAsDataURL(file);
    }

    async function scanKTP() {
        const input = document.getElementById('ktp');
        if (!input.files.length) {
            alert('Pilih gambar KTP dulu.');
            return;
        }

        const file = input.files[0];
        const reader = new FileReader();

        $('#loading-ocr').show();

        reader.onload = async function () {
            const ocrResult = await Tesseract.recognize(
                reader.result,
                'ind',
                { logger: () => {} }
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
            if (jk) $('input[name="jk"]').val(jk[1].trim());

            $('#loading-ocr').hide();
        };

        reader.readAsDataURL(file);
    }

    function parseTempatTanggalLahir(text) {
        const ttlRegex = /(Tempat\s*\/?\s*Tgl\s*Lahir|TTL)\s*[:\-]?\s*(.+)/i;
        const match = text.match(ttlRegex);
        if (!match) return { tempat: '', tanggal: '' };

        let ttlLine = match[2].trim();
        let tempat = '', tanggal = '';

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

        return { tempat, tanggal };
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

    function initMap(lat = -6.2088, lng = 106.8456) {
        map = L.map('map').setView([lat, lng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            $('#latitude').val(lat);
            $('#longitude').val(lng);
        });

        marker.on('dragend', function () {
            const { lat, lng } = marker.getLatLng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);
        });
    }

    function loadWilayah() {
        $.getJSON("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function (data) {
            $('#provinsi').html('<option value="">Pilih Provinsi</option>');
            $.each(data, function (i, p) {
                $('#provinsi').append(`<option value="${p.id}">${p.name}</option>`);
            });
        });

        $('#provinsi').change(function () {
            $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`, function (data) {
                $('#kabupaten').html('<option value="">Pilih Kabupaten</option>');
                $.each(data, function (i, k) {
                    $('#kabupaten').append(`<option value="${k.id}">${k.name}</option>`);
                });
            });
        });

        $('#kabupaten').change(function () {
            $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`, function (data) {
                $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                $.each(data, function (i, kc) {
                    $('#kecamatan').append(`<option value="${kc.id}">${kc.name}</option>`);
                });
            });
        });

        $('#kecamatan').change(function () {
            $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.value}.json`, function (data) {
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
                $.each(data, function (i, kl) {
                    $('#kelurahan').append(`<option value="${kl.id}">${kl.name}</option>`);
                });
            });
        });
    }

    function getCurrentLocationAndAddress() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                $('#latitude').val(lat);
                $('#longitude').val(lng);
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 15);

                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                    const data = await res.json();
                    $('#alamat_sekarang').val(data.display_name || '');
                } catch (err) {
                    console.error("Gagal ambil alamat otomatis:", err);
                }
            });
        }
    }

    $(document).ready(function () {
        initMap();
        loadWilayah();
        getCurrentLocationAndAddress();
    });
    
</script>
