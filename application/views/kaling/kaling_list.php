<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Data Kepala Lingkungan</h4>
                    <a href="<?= base_url('dashboard/kaling/create') ?>" class="btn btn-primary">Tambah Kaling</a>
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
                                <th>Nama</th>
                                <th>Wilayah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($kaling as $row): ?>
                                <tr>
                                    <td><?= $row->nama ?></td>
                                    <td><?= $row->wilayah ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('dashboard/kaling/edit/'.$row->id) ?>" class="btn btn-warning btn-sm me-1">Edit</a>
                                        <button onclick="hapusKaling(<?= $row->id ?>)" class="btn btn-danger btn-sm">Hapus</button>
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

<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });

    function hapusKaling(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('dashboard/kaling/delete/') ?>' + id;
            }
        });
    }
</script>
