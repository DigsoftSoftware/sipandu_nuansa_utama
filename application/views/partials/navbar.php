<!--  Header Start -->
<header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <div class="nav-item dropdown position-relative">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ti ti-menu-2"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><h6 class="dropdown-header">Notifikasi</h6></li>
                  <li><a class="dropdown-item" href="#">Notifikasi 1</a></li>
                  <li><a class="dropdown-item" href="#">Notifikasi 2</a></li>
                  <li><a class="dropdown-item" href="#">Notifikasi 3</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-center" href="#">Lihat Semua</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="<?= base_url('/assets/images/profile/user-1.jpg'); ?>" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                    <div class="message-body text-center">
                        <img src="<?= base_url('/assets/images/profile/user-1.jpg'); ?>" alt="User Profile" width="50" height="50" class="rounded-circle mb-2">
                        <p class="mb-0 fw-semibold">I Putu Agus Wiadnyana</p>
                        <p class="text-muted small">email@example.com</p>
                        <hr class="my-2">
                        <button class="btn btn-outline-primary w-50">Logout</button>
                    </div>
                </div>
              </li>
            </ul>
        </div>
    </nav>
</header>
<!--  Header End -->

<script>
  $(document).ready(function () {
    $("#headerCollapse").click(function (e) {
      e.preventDefault(); 
      $(this).next(".dropdown-menu").toggleClass("show");
    });
    $(document).click(function (e) {
      if (!$(e.target).closest(".dropdown").length) {
        $(".dropdown-menu").removeClass("show");
      }
    });
  });
</script>