<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Tambah Kepala Lingkungan</h4>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('dashboard/kaling/store') ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Handphone</label>
                        <input type="text" name="no_hp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wilayah</label>
                        <select class="form-select" name="wilayah_id" required>
                            <option value="">Pilih Wilayah</option>
                            <?php foreach ($wilayah as $w): ?>
                                <option value="<?= $w->id ?>" <?= (isset($kaling) && $kaling->wilayah_id == $w->id) ? 'selected' : '' ?>>
                                    <?= $w->wilayah ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                        </div>

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

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('dashboard/kaling/view') ?>" class="btn btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
        initMap();
        getCurrentLocationAndAddress();
    });
    
let map, marker;

async function getAddressFromCoordinates(lat, lng) {
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
        const data = await res.json();
        return data.display_name || '';
    } catch (err) {
        console.error("Error getting address:", err);
        return '';
    }
}

async function searchLocation() {
    const address = $('#alamat').val();
    if (!address) {
        alert('Masukkan alamat terlebih dahulu');
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
            $('#alamat').val(data[0].display_name);
        } else {
            alert('Alamat tidak ditemukan');
        }
    } catch (err) {
        console.error("Error searching location:", err);
        alert('Gagal mencari lokasi');
    }
}

function initMap(lat = -8.6726, lng = 115.2088) { // Default to Denpasar coordinates
    try {
        map = L.map('map').setView([lat, lng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        map.on('click', async function (e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            $('#latitude').val(parseFloat(lat).toFixed(8));
            $('#longitude').val(parseFloat(lng).toFixed(8));
            
            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat').val(address);
        });

        marker.on('dragend', async function () {
            const { lat, lng } = marker.getLatLng();
            $('#latitude').val(parseFloat(lat).toFixed(8));
            $('#longitude').val(parseFloat(lng).toFixed(8));
            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat').val(address);
        });
    } catch (err) {
        console.error("Error initializing map:", err);
        alert('Gagal menginisialisasi peta');
    }
}

function getCurrentLocationAndAddress() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async function (position) {
                try {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    $('#latitude').val(parseFloat(lat).toFixed(8));
                    $('#longitude').val(parseFloat(lng).toFixed(8));
                    
                    if (marker && map) {
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 15);
                    }

                    const address = await getAddressFromCoordinates(lat, lng);
                    $('#alamat').val(address);
                } catch (err) {
                    console.error("Error setting current location:", err);
                }
            },
            function (error) {
                console.error("Geolocation error:", error);
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        alert("Izin akses lokasi ditolak");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Informasi lokasi tidak tersedia");
                        break;
                    case error.TIMEOUT:
                        alert("Waktu permintaan lokasi habis");
                        break;
                    default:
                        alert("Terjadi kesalahan saat mengambil lokasi");
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        alert("Browser Anda tidak mendukung geolokasi");
    }
}
</script>