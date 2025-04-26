<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="mb-4">
        <h2>Selamat Datang di Dashboard, <?= $this->session->userdata('username'); ?></h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary shadow rounded-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Total Admin</h5>
                        <h3 class="mb-0"><?= $total_admin; ?></h3>
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
                        <h3 class="mb-0"><?= $total_kaling; ?></h3>
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
                        <h3 class="mb-0"><?= $total_wilayah; ?></h3>
                    </div>
                    <i class="fas fa-map-marked-alt fa-3x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger shadow rounded-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Total Penghuni</h5>
                        <h3 class="mb-0"><?= $total_users; ?></h3>
                    </div>
                    <i class="fas fa-user-friends fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow rounded-3">
                <div class="card-body">
                    <h4 class="card-title">Peta Penghuni Baru</h4>
                    <div id="map" style="height: 400px; z-index: 1; width: 100%;"></div>
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

    var penghuniBaru = <?= json_encode($penghuni_baru) ?>;
    
    // Group markers that are too close to each other
    var markers = L.markerClusterGroup();
    
    penghuniBaru.forEach(function(p) {
        // Create custom icon with adjusted positioning
        var customIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color: #4e73df; color: white; padding: 5px; border-radius: 5px; font-size: 12px; white-space: nowrap;'>" + p.nama + "</div>",
            iconSize: [200, 20],
            iconAnchor: [100, 10] // Center the icon horizontally
        });

        // Add marker to the cluster group
        markers.addLayer(L.marker([p.lat, p.lng], {icon: customIcon})
            .bindPopup("<div style='text-align: center;'><strong>" + p.nama + "</strong><br>Penghuni Baru</div>"));
    });

    // Add the marker cluster group to the map
    map.addLayer(markers);
    
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>

<style>
    .custom-div-icon {
        background: none;
        border: none;
    }
    /* Ensure markers don't overlap */
    .leaflet-div-icon {
        background: transparent;
        border: none;
    }
    /* Override cluster group styles if needed */
    .marker-cluster-small {
        background-color: rgba(77, 115, 223, 0.6);
    }
    .marker-cluster-small div {
        background-color: rgba(77, 115, 223, 0.6);
    }
    .marker-cluster span {
        color: white;
    }
</style>
