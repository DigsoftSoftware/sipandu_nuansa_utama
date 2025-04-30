<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4>Data Wilayah</h4>
                <a href="<?= base_url('dashboard/wilayah/create') ?>" class="btn btn-primary">Tambah Wilayah</a>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <script>
                    Swal.fire("Sukses", "<?= $this->session->flashdata('success') ?>", "success");
                </script>
            <?php endif; ?>

            <table id="myTable" class="table table-striped table-bordered text-nowrap align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Wilayah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($wilayah as $i => $w): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $w->wilayah ?></td>
                        <td>
                            <a href="<?= base_url('dashboard/wilayah/edit/' . $w->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <button onclick="hapusWilayah(<?= $w->id ?>)" class="btn btn-danger btn-sm">Hapus</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>


<script>
     $(document).ready(function () {
        $('#myTable').DataTable();
    });

    function hapusWilayah(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('dashboard/wilayah/delete/') ?>' + id;
            }
        });
    }
</script>
