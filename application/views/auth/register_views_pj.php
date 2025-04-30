<?php $this->load->view('partials/header'); ?>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3">
          <div class="card mb-0">
            <div class="card-body">
              <a href="<?= base_url(); ?>" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="<?= base_url('assets/images/logos/icon.png'); ?>" width="180" alt="">
              </a>
              <p class="text-center">Daftar Akun Khusus Penanggung Jawab di Wilayah Nuansa Utama</p>

              <div id="form-errors" class="text-danger mb-3">
                 <ul class="mb-0" id="errorList" style="list-style: none; padding-left: 0;"></ul>
              </div>

              <form method="post" action="<?= base_url('daftar/submit_pj') ?>" id="daftarPjForm">
                  <input type="hidden" name="token" value="<?= $token ?>">
                  <div class="mb-3">
                      <label class="form-label">Username</label>
                      <input type="text" name="username" class="form-control" required>
                  </div>
                  <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="mb-3 position-relative">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                    <input type="password" name="password" class="form-control" id="inputPassword" required>
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                      <i class="bi bi-eye-slash" id="iconPassword"></i>
                    </span>
                    </div>
                  </div>
                  <div class="mb-3">
                      <label class="form-label">Nama Lengkap</label>
                      <input type="text" name="nama" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Wilayah</label>
                    <select class="form-select" name="wilayah_id" required>
                      <option value="">Pilih Wilayah</option>
                      <?php foreach ($wilayah as $w): ?>
                        <option value="<?= $w->id ?>"><?= $w->wilayah ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Lokasi (Klik pada peta)</label>
                      <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>  
                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                    </div>
               <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Daftar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('partials/footer'); ?>

<script>
  $('#togglePassword').on('click', function () {
    const $passwordInput = $('#inputPassword');
    const $icon = $('#iconPassword');
    const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
    $passwordInput.attr('type', type);
    $icon.toggleClass('bi-eye bi-eye-slash');
  });

  $('#daftarPjForm').submit(function(e) {
    e.preventDefault();
    let form = $(this);
    let formData = form.serialize();

    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            $('#errorList').empty(); 
            if (response.status === 'error') {
                $.each(response.errors, function(index, error) {
                    $('#errorList').append('<li>' + error + '</li>');
                });
            } else if (response.status === 'success') {
                alert(response.message);
                window.location.href = "<?= base_url('auth') ?>";
            }
        },
        error: function() {
            $('#errorList').html("<li class='text-danger'>❌ Terjadi kesalahan saat mengirim data.</li>");
        }
    });
});

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
