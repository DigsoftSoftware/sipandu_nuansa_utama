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
              <p class="text-center">Daftar Akun Khusus Penanggung Jawab di Wilayah Nuansa Utama</p>
              
              <form method="post" action="<?= base_url('daftar/submit_pj') ?>">
                  <input type="hidden" name="token" value="<?= $token ?>">
                      <div class="mb-3">
                          <label class="form-label">Username</label>
                          <input type="text" name="username" class="form-control" required>
                      </div>
                      <div class="mb-3 position-relative">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                        <input type="password" name="password" class="form-control" id="inputPassword" required>
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                          <i class="bi bi-eye-slash" id="iconPassword"></i>
                        </span>
                        </div>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Nama Lengkap</label>
                          <input type="text" name="nama" class="form-control" required>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Alamat</label>  
                          <textarea name="alamat" class="form-control" required></textarea>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Nomor HP</label>
                          <input type="text" name="no_hp" class="form-control" required>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Wilayah</label>
                          <select class="form-select" name="wilayah_id" required>
                              <option value="">Pilih Wilayah</option>
                              <?php foreach ($wilayah as $w): ?>
                                  <option value="<?= $w->id ?>"><?= $w->wilayah ?></option>
                              <?php endforeach; ?>
                          </select>
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

<script>
  $('#togglePassword').on('click', function () {
    const $passwordInput = $('#inputPassword');
    const $icon = $('#iconPassword');
    const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
    $passwordInput.attr('type', type);
    $icon.toggleClass('bi-eye bi-eye-slash');
  });
</script>

