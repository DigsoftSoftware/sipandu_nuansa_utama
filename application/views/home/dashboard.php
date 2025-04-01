<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="datatables">
                <!-- start Zero Configuration -->
                <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Zero Configuration</h4>
                    <p class="card-subtitle mb-3">
                    DataTables has most features enabled by default, so all
                    you need to do to use it with your own tables is to call
                    the construction function:<code> $().DataTable();</code>. You can refer full documentation from
                    here
                    <a href="https://datatables.net/">Datatables</a>
                    </p>
                    <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <!-- start row -->
                        <tr>
                            <td>
                            <div class="d-flex align-items-center gap-6">
                                <img src="../assets/images/profile/user-2.jpg" width="45" class="rounded-circle" />
                                <h6 class="mb-0"> Garrett Winters</h6>
                            </div>
                            </td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                        </tr>
                        <!-- end row -->
                        <!-- start row -->
                        <tr>
                            <td>
                            <div class="d-flex align-items-center gap-6">
                                <img src="../assets/images/profile/user-3.jpg" width="45" class="rounded-circle" />
                                <h6 class="mb-0"> Ashton Cox</h6>
                            </div>
                            </td>
                            <td>Junior Technical Author</td>
                            <td>San Francisco</td>
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
                        </tr>
                        <!-- end row -->
                        <!-- start row -->
                        <tr>
                            <td>
                            <div class="d-flex align-items-center gap-6">
                                <img src="../assets/images/profile/user-7.jpg" width="45" class="rounded-circle" />
                                <h6 class="mb-0">Donna Snider</h6>
                            </div>
                            </td>
                            <td>Customer Support</td>
                            <td>New York</td>
                            <td>27</td>
                            <td>2011/01/25</td>
                            <td>$112,000</td>
                        </tr>
                        <!-- end row -->
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
                <!-- end Zero Configuration -->
</div>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>



<?php $this->load->view('partials/footer'); ?>
