<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="mb-4">
        <h2>Selamat Datang di Admin, <?= $this->session->userdata('username'); ?></h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary shadow rounded-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Total Admin</h5>
                        <h3 class="mb-0">12</h3>
                    </div>
                    <i class="fas fa-user fa-3x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success shadow rounded-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Total Kaling</h5>
                        <h3 class="mb-0">24</h3>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning shadow rounded-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Wilayah</h5>
                        <h3 class="mb-0">8</h3>
                    </div>
                    <i class="fas fa-map-marked-alt fa-3x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger shadow rounded-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Total Users</h5>
                        <h3 class="mb-0">120</h3>
                    </div>
                    <i class="fas fa-user-friends fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow rounded-3">
                <div class="card-body">
                    <h4 class="card-title">Peta Penghuni Baru</h4>
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow rounded-3">
                <div class="card-body">
                    <h4 class="card-title">Data Penghuni Baru</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="myTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><img src="<?= base_url('assets/images/profile/user-1.jpg') ?>" width="50" height="50" class="rounded-circle object-fit-cover" style="object-fit: cover;" alt="Kadek"></td>
                                    <td>Kadek</td>
                                    <td>Denpasar</td>
                                </tr>
                                <tr>
                                    <td><img src="<?= base_url('assets/images/profile/user-1.jpg') ?>" width="50" height="50" class="rounded-circle object-fit-cover" style="object-fit: cover;" alt="Komang"></td>
                                    <td>Komang</td>
                                    <td>Denpasar</td>
                                </tr>
                                <tr>
                                    <td><img src="<?= base_url('assets/images/profile/user-1.jpg') ?>" width="50" height="50" class="rounded-circle object-fit-cover" style="object-fit: cover;" alt="Kadek"></td>
                                    <td>Kadek</td>
                                    <td>Denpasar</td>
                                </tr>
                                <tr>
                                    <td><img src="<?= base_url('assets/images/profile/user-1.jpg') ?>" width="50" height="50" class="rounded-circle object-fit-cover" style="object-fit: cover;" alt="Komang"></td>
                                    <td>Komang</td>
                                    <td>Denpasar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>

<script>
    var map = L.map('map').setView([-8.6525, 115.2190], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var penghuniBaru = [
        { nama: "Kadek", lat: -8.6530, lng: 115.2175 },
        { nama: "Komang", lat: -8.6510, lng: 115.2210 }
    ];
    penghuniBaru.forEach(function(p) {
        L.marker([p.lat, p.lng]).addTo(map)
            .bindPopup("<b>" + p.nama + "</b><br>Penghuni Baru");
    });
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
