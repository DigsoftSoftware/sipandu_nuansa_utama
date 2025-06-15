<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>
<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Edit Admin</h5>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('dashboard/admin/update/' . $admin->uuid) ?>" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama" value="<?= $admin->nama ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" value="<?= $admin->username ?>" required>
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="inputPassword">
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="iconPassword"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-secondary">Update</button>
                    <a href="<?= base_url('dashboard/admin/view') ?>" class="btn btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function() {
        $('#togglePassword').on('click', function() {
            const $passwordInput = $('#inputPassword');
            const $icon = $('#iconPassword');
            const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
            $passwordInput.attr('type', type);
            $icon.toggleClass('bi-eye bi-eye-slash');
        });

        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '<?= $this->session->flashdata('success'); ?>',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    });
</script>