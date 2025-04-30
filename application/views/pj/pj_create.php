<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Tambah Penanggung Jawab</h5>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?= base_url('dashboard/pj/store') ?>">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="wilayah_id" class="form-label">Wilayah</label>
                    <select class="form-control" id="wilayah_id" name="wilayah_id" required>
                        <option value="">Pilih Wilayah</option>
                        <?php foreach($wilayah as $w): ?>
                              <option value="<?= $w->id ?>"><?= $w->wilayah ?></option>
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
                <a href="<?= base_url('dashboard/pj/view') ?>" class="btn btn-danger">Kembali</a>
            </form>
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
    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
    const data = await res.json();
    return data.display_name || '';
}

async function searchLocation() {
    const address = $('#alamat').val();
    if (!address) {
        alert('Masukkan alamat terlebih dahulu');
        return;
    }

    const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
    const data = await res.json();
    
    if (data && data.length > 0) {
        const { lat, lon } = data[0];
        marker.setLatLng([lat, lon]);
        map.setView([lat, lon], 15);
        $('#latitude').val(lat);
        $('#longitude').val(lon);
        $('#alamat').val(data[0].display_name);
    } else {
        alert('Alamat tidak ditemukan');
    }
}

function initMap(lat = -6.2088, lng = 106.8456) {
    map = L.map('map').setView([lat, lng], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    map.on('click', async function (e) {
        const { lat, lng } = e.latlng;
        marker.setLatLng([lat, lng]);
        $('#latitude').val(lat);
        $('#longitude').val(lng);
        
        const address = await getAddressFromCoordinates(lat, lng);
        $('#alamat').val(address);
    });

    marker.on('dragend', async function () {
        const { lat, lng } = marker.getLatLng();
        $('#latitude').val(lat);
        $('#longitude').val(lng);
        const address = await getAddressFromCoordinates(lat, lng);
        $('#alamat').val(address);
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
                    $('#alamat').val(data.display_name || '');
                } catch (err) {
                    console.error("Gagal ambil alamat otomatis:", err);
                }
            });
        }
    }


</script>