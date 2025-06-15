<?php $this->load->view('partials/header'); ?>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="<?= base_url('assets/images/logos/icon.png'); ?>" width="180" alt="">
              </div>
              <p class="text-center">Daftar Akun Khusus Penanggung Jawab di Wilayah Nuansa Utama</p>

              <div id="form-errors" class="text-danger mb-3 d-none">
                <ul class="mb-0" id="errorList" style="list-style: none; padding-left: 0;"></ul>
              </div>

              <form method="post" action="<?= base_url('daftar/submit_pj') ?>" id="daftarPjForm">
                <input type="hidden" name="token" value="<?= $token ?>">
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" id="username" required>
                  <div class="invalid-feedback" id="username-error"></div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="email" required>
                  <div class="invalid-feedback" id="email-error"></div>
                </div>
                <div class="mb-3 position-relative">
                  <label class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" name="password" class="form-control" id="inputPassword" required>
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                      <i class="bi bi-eye-slash" id="iconPassword"></i>
                    </span>
                  </div>
                  <div class="invalid-feedback" id="password-error"></div>
                </div>
                <div class="mb-4">
                  <label class="form-label">Wilayah</label>
                  <select class="form-select" name="wilayah_id" id="wilayah_id" required>
                    <option value="">Pilih Wilayah</option>
                    <?php foreach ($wilayah as $w): ?>
                      <option value="<?= $w->id ?>"><?= $w->wilayah ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback" id="wilayah-error"></div>
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

<?php $this->load->view('partials/footer'); ?>

<script>
  $('#togglePassword').on('click', function () {
    const $passwordInput = $('#inputPassword');
    const $icon = $('#iconPassword');
    const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
    $passwordInput.attr('type', type);
    $icon.toggleClass('bi-eye bi-eye-slash');
  });

  $('#daftarPjForm').submit(function (e) {
    e.preventDefault();
    let form = $(this);
    let formData = form.serialize();

    $('.form-control, .form-select').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('#errorList').empty();
    $('#form-errors').addClass('d-none');

    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function (response) {
        if (response.status === 'error') {
          if (response.errors) {
            $.each(response.errors, function (field, message) {
              if ($('#' + field).length) {
                $('#' + field).addClass('is-invalid');
                $('#' + field + '-error').text(message);
              } else {
                $('#form-errors').removeClass('d-none');
                $('#errorList').append('<li>' + message + '</li>');
              }
            });
          }
        } else if (response.status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Pendaftaran Berhasil!',
            text: 'Akun anda telah berhasil dibuat. Silakan login.',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "<?= base_url('auth') ?>";
            }
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Terjadi kesalahan saat mengirim data!',
          confirmButtonText: 'Tutup'
        });
      }
    });
  });
</script>
