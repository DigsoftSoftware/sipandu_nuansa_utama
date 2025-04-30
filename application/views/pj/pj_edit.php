<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Data Penanggung Jawab</h4>
                <form action="<?= base_url('dashboard/pj/update/'.$pj->id) ?>" method="post">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= $pj->nama ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $pj->email ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Jika tidak mengubah, bisa dikosongkan!)</label>
                        <input type="password" name="password" class="form-control" value="">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Handphone</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= $pj->no_hp ?>" required>
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
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                    <a href="<?= base_url('dashboard/pj/view') ?>" class="btn btn-danger mt-3">Kembali</a>
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