<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5.0.1/dist/tesseract.min.js"></script>
<script src="<?= base_url('/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('/assets/js/sidebarmenu.js'); ?>"></script>
<script src="<?= base_url('/assets/js/app.min.js'); ?>"></script>
<script src="<?= base_url('/assets/libs/apexcharts/dist/apexcharts.min.js'); ?>"></script>
<script src="<?= base_url('/assets/libs/simplebar/dist/simplebar.js'); ?>"></script>
<script src="<?= base_url('/assets/js/dashboard.js'); ?>"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


<script>
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

    $('.delete-button').on('click', function (e) {
      e.preventDefault();
      const href = $(this).attr('href');
      Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = href;
        }
      });
    });
  });

  <?php if ($this->session->flashdata('success_login')): ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil Login',
        text: '<?= $this->session->flashdata('success_login'); ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
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
