<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="<?= base_url('/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('/assets/js/sidebarmenu.js'); ?>"></script>
<script src="<?= base_url('/assets/js/app.min.js'); ?>"></script>
<script src="<?= base_url('/assets/libs/apexcharts/dist/apexcharts.min.js'); ?>"></script>
<script src="<?= base_url('/assets/libs/simplebar/dist/simplebar.js'); ?>"></script>
<script src="<?= base_url('/assets/js/dashboard.js'); ?>"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


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

  $(document).ready(function () {
    $('#logoutButton').on('click', function () {
      Swal.fire({
        title: 'Apakah Anda akan logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, logout!',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          $('#logoutForm').submit();
        }
      });
    });
  });

  <?php if ($this->session->flashdata('success')): ?>
    Swal.fire({
      icon: 'success',
      title: 'Logout Berhasil!',
      text: '<?= $this->session->flashdata('success'); ?>',
      position: 'center',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  <?php endif; ?>

</script>

</body>
</html>
