<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('pj/sidebar_pj'); ?>
<?php $this->load->view('pj/navbar'); ?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="datatables">
                <!-- start Zero Configuration -->
                <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Kaling</h4>
                    <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Wilayah</th>
                        </tr>
                        </thead>
                        <tr>
                            <td>I Putu Agus Wiadnyana</td>
                            <td>Tokyo</td>
                        </tr>
                        <tr>
                            <td>I Putu Agus Wiadnyana</td>
                            <td>Tokyo</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
           </div>
                <!-- end Zero Configuration -->
        </div>

<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
