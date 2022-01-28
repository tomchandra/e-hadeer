<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="<?= base_url('library/bootstrap/css/bootstrap.min.css'); ?>" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('library/bootstrap/css/bootstrap-datepicker3.min.css'); ?>" crossorigin="anonymous">


    <link rel="stylesheet" href="<?= base_url('library/fontawesome/css/all.min.css'); ?>" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= base_url('library/stisla/css/style.css'); ?>" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('library/stisla/css/components.css'); ?>" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('css/custom.css'); ?>" crossorigin="anonymous">\

    <link rel="stylesheet" href="<?= base_url('library/datatables/jquery.dataTables.min.css'); ?>" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= base_url('library/select2/css/select2.min.css'); ?>" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('css/loader.css'); ?>" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('css/menu.css'); ?>" crossorigin="anonymous">

    <?= $this->renderSection('css'); ?>

    <meta name="csrf_token_name" content="<?= csrf_hash() ?>">
    <title>e-Hadeer</title>

</head>

<body>
    <div class="loader">
        <div class="cssload-loader">
            <div class="cssload-inner cssload-text">
                e-Hadeer
            </div>
            <div class="cssload-inner cssload-line"></div>
        </div>
    </div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?= $this->include('themes/navbar'); ?>
            <?= $this->include('themes/sidebar'); ?>
            <?= $this->renderSection('content'); ?>
            <?= $this->include('themes/footer'); ?>
        </div>
    </div>
    <script src="<?= base_url('library/jquery/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?= base_url('library/stisla/js/popper.js'); ?>"></script>
    <script src="<?= base_url('library/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('library/bootstrap/js/bootstrap-datepicker.min.js'); ?>"></script>
    <script src="<?= base_url('library/jquery/jquery.nicescroll.min.js'); ?>"></script>
    <script src="<?= base_url('library/jquery/jquery.autocomplete.js'); ?>"></script>
    <script src="<?= base_url('library/jquery/webcam.min.js'); ?>"></script>
    <script src="<?= base_url('library/jquery/jquery-freeze-table.js'); ?>"></script>


    <!-- templatejs -->
    <script src="<?= base_url('library/stisla/js/stisla.js'); ?>"></script>
    <script src="<?= base_url('library/stisla/js/scripts.js'); ?>"></script>
    <script src="<?= base_url('library/stisla/js/custom.js'); ?>"></script>

    <!-- datatablejs -->
    <script src="<?= base_url('library/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?= base_url('library/datatables/dataTables.bootstrap4.min.js'); ?>"></script>
    <script src="<?= base_url('library/datatables/dataTables.select.min.js'); ?>"></script>
    <script src="<?= base_url('library/datatables/dataTables.rowGroup.min.js'); ?>"></script>

    <script src="<?= base_url('library/alert/sweetalert2.all.min.js'); ?>"></script>

    <script src="<?= base_url('library/select2/js/select2.min.js'); ?>"></script>
    <script src="<?= base_url('js/moment.js'); ?>"></script>
    <script src="<?= base_url('js/treemenu.js'); ?>"></script>
    <script src="<?= base_url('js/custom.js'); ?>"></script>
    <script>
        let base_url = "<?= base_url() ?>";
        $(window).on("load", function() {
            $(".loader").fadeOut("slow");
        });

        $(window).on("unload", function() {
            $(".loader").fadeIn();
        });
    </script>
    <?= $this->renderSection('extra-js'); ?>
</body>

</html>