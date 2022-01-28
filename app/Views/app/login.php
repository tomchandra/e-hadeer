<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="<?= base_url('library/bootstrap/css/bootstrap.min.css'); ?>" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('css/loader.css'); ?>" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('css/login.css'); ?>" crossorigin="anonymous">
    <meta name="csrf_token_name" content="<?= csrf_hash() ?>">
    <title>e-Hadeer</title>
</head>

<body>
    <div id="main-wrapper" class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="p-5">
                                    <div class="mb-5">
                                        <h3 class="h4 font-weight-bold text-theme">Login e-Hadeer</h3>
                                    </div>
                                    <?php if (session()->getFlashdata('msg')) : ?>
                                        <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
                                    <?php endif; ?>
                                    <h6 class="h5 mb-0">Welcome back!</h6>
                                    <p class="text-muted mt-2 mb-5">Enter your usename and password to access attendance.</p>

                                    <form method="POST" action="<?= base_url('auth'); ?>">
                                        <div class="form-group">
                                            <label for="input-username">Username</label>
                                            <input type="text" name="input_username" class="form-control" id="input-username" required>
                                        </div>
                                        <div class="form-group mb-5">
                                            <label for="input-password">Password</label>
                                            <input type="password" name="input_password" class="form-control" id="input-password" required>
                                        </div>
                                        <button type="submit" class="btn btn-theme">Login</button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 d-none d-lg-inline-block">
                                <div class="account-block rounded-right">
                                    <img alt="image" src="<?= base_url('images/attendance-2.jpg') ?>" class="img-fluid w-100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('library/jquery/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?= base_url('library/stisla/js/popper.js'); ?>"></script>
    <script src="<?= base_url('library/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('library/jquery/jquery.nicescroll.min.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            $('.loader').fadeOut();
        });
    </script>
</body>

</html>