<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('admin/sidebar_admin'); ?>
<?php $this->load->view('partials/navbar'); ?>

<!-- Main Content -->
    <div class="container-fluid">
        <div class="datatables">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Admin</h4>
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
        </div>
    </div>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
