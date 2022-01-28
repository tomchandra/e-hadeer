<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav mr-auto">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="<?= base_url('library/stisla/img/avatar/avatar-1.png'); ?>" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, <?= session()->get('user_name') ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="<?= base_url("/app/presence") ?>" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url("/logout") ?>" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>