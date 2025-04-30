<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Data Admin</h4>
                    <a href="<?= base_url('dashboard/admin/create') ?>" class="btn btn-primary">Tambah Admin</a>
                </div>

                <?php if($this->session->flashdata('success')): ?>
                    <script>
                        Swal.fire("Sukses", "<?= $this->session->flashdata('success') ?>", "success");
                    </script>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($admins as $i => $admin): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= $admin->nama ?></td>
                                    <td><?= $admin->username ?></td>
                                    <td class="text-center">
                                        <?php if ($admin->id != $this->session->userdata('admin_id')): ?>
                                            <a href="<?= base_url('dashboard/admin/edit/'.$admin->id) ?>" class="btn btn-warning btn-sm me-1">Edit</a>
                                            <button onclick="hapusAdmin(<?= $admin->id ?>)" class="btn btn-danger btn-sm">Hapus</button>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Sedang Login</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });

    function hapusAdmin(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('dashboard/admin/delete/') ?>' + id;
            }
        });
    }
</script>
