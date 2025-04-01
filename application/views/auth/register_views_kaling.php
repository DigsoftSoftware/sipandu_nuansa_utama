<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3">
          <div class="card mb-0">
            <div class="card-body">
              <a href="<?= base_url(); ?>" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="<?= base_url('assets/images/logos/icon.png'); ?>" width="180" alt="">
              </a>
              <p class="text-center">Daftar Akun Khusus Kepala Lingkungan di Wilayah Nuansa Utama</p>
              <form action="<?= base_url('auth/login_process'); ?>" method="post">
                <div class="mb-3">
                  <label for="username" class="form-label">Nama Kepala Lingkungan</label>
                  <input type="email" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">No Telepon</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Email</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Daftar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
