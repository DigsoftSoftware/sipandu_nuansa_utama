<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Data Penanggung Jawab</h4>
                <a href="<?= base_url('dashboard/pj/create') ?>" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Tambah Penanggung Jawab
                </a>
            </div>

            <?php if($this->session->flashdata('success')): ?>
                <script>Swal.fire("Sukses", "<?= $this->session->flashdata('success') ?>", "success");</script>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="pjTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>No Handphone</th>
                            <th>Wilayah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pj as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= $p->nama ?></td>
                            <td><?= $p->username ?></td>
                            <td><?= $p->email ?></td>    
                            <td><?= $p->no_hp ?></td>
                            <td><?= $p->wilayah_nama ?></td>
                            <td>
                                <a href="<?= base_url('dashboard/pj/edit/'.$p->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button onclick="hapusPJ(<?= $p->id ?>)" class="btn btn-danger btn-sm">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
        $('#pjTable').DataTable();
    });

    function hapusPJ(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('dashboard/pj/delete/') ?>' + id;
            }
        });
    }
</script>
