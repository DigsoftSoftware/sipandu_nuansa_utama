<!-- Header Start -->
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" href="#" id="headerCollapse">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>

        </ul>

        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item">
                    <form id="logoutForm" action="<?= base_url('auth/logout') ?>" method="post" class="d-inline">
                        <button type="button" id="logoutButton" class="btn btn-outline-danger d-flex align-items-center">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- Header End -->
