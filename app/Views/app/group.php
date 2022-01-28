<?= $this->extend('themes/theme'); ?>
<?= $this->section('content'); ?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="section-title fix-title">Management Group</h2>
                    </div>
                    <div class="card-footer bg-whitesmoke pt-3 pb-2 d-flex align-items-end flex-column">

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="card-form-add" class="card card-info" style="display: none;">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="input-group-name" class="col-3 col-form-label">Group Name</label>
                            <div class="col-9">
                                <div class="input-group mb-3">
                                    <input type="text" id="input-group-name" class="form-control form-control-sm" placeholder="" aria-label="">
                                    <div class="input-group-append">
                                        <button id="btn-add-group-name" class="btn btn-outline-primary" type="button">Save</button>
                                        <button id="btn-cancel-group-name" class="btn btn-outline-primary" type="button">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA LIST -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info">
                    <div class="card-header">
                        <div class="col-12 p-0 d-flex align-items-end flex-column">
                            <div class="buttons">
                                <button id="btn-add-group" class="btn btn-outline-primary note-btn">Create Group</button>
                                <button id="btn-update-group" class="btn btn-outline-primary note-btn d-none">Update</button>
                                <!-- <button id="btn-delete-group" class="btn btn-outline-primary note-btn d-none">Delete</button> -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="section-title fix-title">Group Access</div>
                        <hr />
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <?php $toltip = 'data-toggle="tooltip" data-placement="right" title="" data-original-title="Clik to edit this menu"'; ?>
                                <ul class="list-group list-group-flush">
                                    <?php if (count($group) > 0) : ?>
                                        <?php foreach ($group as $key => $value) : ?>
                                            <li class="list-group-item"><a href="#" class="data-group" data-groupid="<?= $value["group_id"] ?>"><?= $value["group_cd"] ?></a></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 group-menu-list d-none">
                                <div class="p-3 rounded">
                                    <form>
                                        <ul class="tree">
                                            <li><span id="tree-group-name"></span>
                                                <ul>
                                                    <?php if (count($data_menu) > 0) : ?>
                                                        <?php foreach ($data_menu as $key => $parent) : ?>
                                                            <?php if (count($parent['menu_child']) > 0) : ?>
                                                                <li><input type="checkbox" name="data_menu" value="<?= $parent['menu_id'] ?>" class="menu-parent"> <span><?= $parent['menu_name'] ?></span>
                                                                    <ul>
                                                                        <?php foreach ($parent['menu_child'] as $key => $child) : ?>
                                                                            <li><input type="checkbox" name="data_menu" value="<?= $child['menu_id'] ?>" data-parentid="<?= $parent['menu_id'] ?>" class="menu-child"> <span><?= $child['menu_name'] ?></span></li>
                                                                        <?php endforeach; ?>
                                                                    </ul>
                                                                </li>
                                                            <?php else : ?>
                                                                <li><input type="checkbox" name="data_menu" value="<?= $parent['menu_id'] ?>"> <span><?= $parent['menu_name'] ?></span></a></li>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </ul>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke"></div>
                </div>
            </div>
        </div>
        <!-- END DATA LIST -->
    </section>
</div>
<?= $this->endSection(); ?>

<?= $this->section('extra-js'); ?>
<script>
    let group_id = null;
    $(document).ready(function() {
        $('.loader').fadeOut();
        init_ajax();

        $('.data-group').click(function(e) {
            e.preventDefault();
            $('.loader').fadeIn();

            $('#btn-update-group').removeClass('d-none');
            $('#btn-delete-group').removeClass('d-none');
            $('.group-menu-list').removeClass('d-none');
            $('#card-form-add').slideUp();

            group_id = $(this).attr('data-groupid');
            let group_text = $(this).text();

            $.ajax({
                url: `${base_url}/app/group/detail-access`,
                type: 'GET',
                data: {
                    group_id: group_id,
                },
                dataType: 'JSON',
                success: function(data) {
                    $('.loader').fadeOut();
                    if (data.message == 'success') {
                        console.log(data.data);
                        $('input[type=checkbox]').prop('checked', false);
                        $.each(data.data, function(i, item) {
                            $('input[name=data_menu][value=' + item.menu_id + ']').prop("checked", true);
                        });
                        $('#tree-group-name').html(group_text);
                    }
                }
            });
        });

        $('#btn-add-group').click(function(e) {
            e.preventDefault();
            $('#btn-update-group').addClass('d-none');
            $('#btn-delete-group').addClass('d-none');
            $('.group-menu-list').addClass('d-none');
            $('input[type=checkbox]').prop('checked', false);
            $('#input-group-name').val('');

            group_id = null;
            let display = $('#card-form-add').css("display");
            if (display == 'none') {
                $('#card-form-add').slideDown();
            } else {
                $('#card-form-add').slideUp();
            }
        });

        $('.menu-parent').change(function(e) {
            e.preventDefault();
            if ($(this).is(':checked')) {
                $('input[name=data_menu][data-parentid=' + $(this).val() + ']').prop("checked", true);
            } else {
                $('input[name=data_menu][data-parentid=' + $(this).val() + ']').prop("checked", false);
            }
        });

        $('.menu-child').change(function(e) {
            e.preventDefault();
            if ($('input[name=data_menu][data-parentid=' + $(this).attr("data-parentid") + ']:checked').length < 1) {
                $('input[name=data_menu][value=' + $(this).attr("data-parentid") + ']').prop("checked", false);
            } else {
                $('input[name=data_menu][value=' + $(this).attr("data-parentid") + ']').prop("checked", true);
            }
        });

        $('#btn-cancel-group-name').click(function(e) {
            e.preventDefault();
            $('#card-form-add').slideUp();
            $('#input-group-name').val('');
        });

        $('#btn-add-group-name').click(function(e) {
            e.preventDefault();
            if ($('#input-group-name').val().length === 0) {
                Swal.fire(
                    'Please fill this field!',
                    'Group Name',
                    'error'
                );
                return;
            }

            Swal.fire({
                title: 'Area you sure want to save changes?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                $('.loader').fadeIn();
                if (result.value) {
                    $.ajax({
                        url: `${base_url}/app/group/add`,
                        type: 'POST',
                        data: {
                            data: $('#input-group-name').val(),
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            $('.loader').fadeOut();
                            if (data.message == 'success') {
                                Swal.fire(
                                    'Success!',
                                    'Data saved!',
                                    'success'
                                ).then((result) => {
                                    if (result.value) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Error :' + data.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });

        $('#btn-update-group').click(function(e) {
            e.preventDefault();
            let data = $('form').serializeArray();
            if (data.length < 1) {
                Swal.fire(
                    'Please choose menu!',
                    'error'
                );
                return;
            }
            Swal.fire({
                title: 'Area you sure want to save changes?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                $('.loader').fadeIn();
                if (result.value) {
                    $.ajax({
                        url: `${base_url}/app/group/add-access`,
                        type: 'POST',
                        data: {
                            group_id: group_id,
                            data: data,
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            $('.loader').fadeOut();
                            if (data.message == 'success') {
                                Swal.fire(
                                    'Success!',
                                    'Data saved!',
                                    'success'
                                ).then((result) => {
                                    if (result.value) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Error :' + data.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });

    });
</script>
<?= $this->endSection(); ?>