<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Edit Kepala Lingkungan</h5>

                <form action="<?= base_url('dashboard/kaling/update/' . $kaling->uuid) ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" value="<?= $kaling->username ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" value="<?= $kaling->nama ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="inputPassword">
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="bi bi-eye-slash" id="iconPassword"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Handphone</label>
                        <input type="text" name="no_hp" value="<?= $kaling->no_hp ?>" class="form-control" required>
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
                        <button type="button" id="search-location" class="btn btn-secondary mt-2">Cari Lokasi</button>
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
                    <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/kaling/view') ?>'">Kembali</button>

                </form>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>


<script>
    $(document).ready(function() {
        initMap();
    });

    function initMap() {
        const lat = parseFloat('<?= $kaling->latitude ?>') || -8.6726;
        const lng = parseFloat('<?= $kaling->longitude ?>') || 115.2088;

        const map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            $('#latitude').val(position.lat.toFixed(8));
            $('#longitude').val(position.lng.toFixed(8));
        });

        map.on('click', function(e) {
            const {
                lat,
                lng
            } = e.latlng;
            marker.setLatLng([lat, lng]);
            $('#latitude').val(lat.toFixed(8));
            $('#longitude').val(lng.toFixed(8));
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

    $('#togglePassword').on('click', function() {
        const passwordField = $('#inputPassword');
        const icon = $('#iconPassword');
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });
    <?php if ($this->session->flashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '<?= $this->session->flashdata('success'); ?>',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
    }
    
</script>