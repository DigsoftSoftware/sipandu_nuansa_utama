<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Kepala Lingkungan</h4>

                <form action="<?= base_url('dashboard/kaling/update/'.$kaling->uuid) ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" value="<?= $kaling->username ?>" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Handphone</label>
                        <input type="text" name="no_hp" value="<?= $kaling->no_hp ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" value="<?= $kaling->nama ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wilayah</label>
                        <select class="form-select" name="wilayah_id" required>
                            <option value="">Pilih Wilayah</option>
                            <?php foreach ($wilayah as $w): ?>
                                <option value="<?= $w->id ?>" <?= ($kaling->wilayah_id == $w->id) ? 'selected' : '' ?>>
                                    <?= $w->wilayah ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required><?= $kaling->alamat ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi (Klik pada peta)</label>
                        <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="<?= $kaling->latitude ?>" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="<?= $kaling->longitude ?>" class="form-control" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
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

</script>