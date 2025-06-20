<?php $this->load->view('partials/header'); ?>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-12 col-lg-12 col-xxl-3">
          <div class="card mb-0">
            <div class="card-body">
              <a href="<?= base_url('auth/process_login') ?>" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="<?= base_url('assets/images/logos/icon.png'); ?>" width="180" alt="">
              </a>
              <p class="text-center">Login - SIPANDU Nuansa Utama</p>
              <form method="post" action="<?= base_url('auth/do_login') ?>">
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-4 position-relative">
                  <label class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" name="password" class="form-control" id="inputPassword" required>
                      <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="bi bi-eye-slash" id="iconPassword"></i>
                      </span>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Login</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('partials/footer'); ?>

<script>
    $(document).ready(function () {
    $('#togglePassword').on('click', function () {
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

    <?php if ($this->session->flashdata('success_login')): ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil Login',
        text: '<?= $this->session->flashdata('success_login'); ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000,
        timerProgressBar: true
      });
    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '<?= $this->session->flashdata('error'); ?>',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    <?php endif; ?>
  });
</script>



