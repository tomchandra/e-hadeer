<?= $this->extend('themes/theme'); ?>
<?= $this->section('content'); ?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="section-title fix-title">Management Menu</h2>
                    </div>
                    <div class="card-footer bg-whitesmoke pt-3 pb-2 d-flex align-items-end flex-column">

                    </div>
                </div>
            </div>
        </div>

        <!-- DATA LIST -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <form>
                                    <div class="form-group">
                                        <label for="input-menu-name">Menu Name</label>
                                        <input type="text" name="menu_cd" class="form-control" id="input-menu-name">
                                    </div>
                                    <div class="form-group">
                                        <label for="input-menu-parent">Menu Parent</label>
                                        <select name="menu_parent_id" id="input-menu-parent" class="form-control select2">
                                            <option></option>
                                            <option value="0">Is Parent</option>
                                            <?php if (count($data_menu) > 0) : ?>
                                                <?php foreach ($data_menu as $key => $parent) : ?>
                                                    <?php if (count($parent['menu_child']) > 0) : ?>
                                                        <optgroup label="<?= $parent['menu_name'] ?>">
                                                            <option value="<?= $parent['menu_id'] ?>"><?= $parent['menu_name'] ?></option>
                                                            <?php foreach ($parent['menu_child'] as $key => $child) : ?>
                                                                <option value="<?= $child['menu_id'] ?>"><?= $child['menu_name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                    <?php else : ?>
                                                        <option value="<?= $parent['menu_id'] ?>"><?= $parent['menu_name'] ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-menu-order">Menu Order</label>
                                        <input type="text" name="menu_order" class="form-control" id="input-menu-order">
                                    </div>
                                    <div class="form-group">
                                        <label for="input-menu-url">Menu Url</label>
                                        <input type="text" name="menu_url" class="form-control" id="input-menu-url">
                                    </div>
                                    <div class="form-group">
                                        <div class="control-label">Status</div>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="status_cd" value="active" class="selectgroup-input">
                                                <span class="selectgroup-button">Active</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="status_cd" value="inactive" class="selectgroup-input">
                                                <span class="selectgroup-button">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </form>
                                <div class="buttons text-right">
                                    <button id="btn-new-menu" class="btn btn-sm btn-outline-primary">New</button>
                                    <button id="btn-save-menu" class="btn btn-sm btn-outline-primary">Save</button>
                                    <button id="btn-update-menu" class="btn btn-sm btn-outline-primary d-none">Update</button>
                                </div>

                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 p-2 bg-whitesmoke rounded">
                                <ul class="tree">
                                    <li>Menu
                                        <ul>
                                            <?php $toltip = 'data-toggle="tooltip" data-placement="right" title="" data-original-title="Clik to edit this menu"'; ?>
                                            <?php if (count($data_menu) > 0) : ?>
                                                <?php foreach ($data_menu as $key => $parent) : ?>
                                                    <?php if (count($parent['menu_child']) > 0) : ?>
                                                        <li><i class="fa fa-folder-open"></i> <a href="#" class="data-menu" data-menuid="<?= $parent['menu_id'] ?>"><span <?= $toltip ?>> <?= $parent['menu_name'] ?></span></a>
                                                            <ul>
                                                                <?php foreach ($parent['menu_child'] as $key => $child) : ?>
                                                                    <li><a href="#" class="data-menu" data-menuid="<?= $child['menu_id'] ?>"><span <?= $toltip ?>><?= $child['menu_name'] ?></span></a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                    <?php else : ?>
                                                        <li><i class="fa fa-folder-open"></i> <a href="#" class="data-menu" data-menuid="<?= $parent['menu_id'] ?>"><span <?= $toltip ?>><?= $parent['menu_name'] ?></span></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                </ul>
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
    let menu_id = null;
    let empty_field = [];

    $(document).ready(function() {
        $('.loader').fadeOut();
        init_ajax();
        $('#input-menu-parent').select2();

        $('.data-menu').click(function(e) {
            e.preventDefault();
            $('.loader').fadeIn();
            menu_id = $(this).attr('data-menuid');
            $('#btn-update-menu').removeClass('d-none');
            $('#btn-save-menu').addClass('d-none');

            $.ajax({
                url: `${base_url}/app/menu/detail`,
                type: 'GET',
                data: {
                    menu_id: menu_id,
                },
                dataType: 'JSON',
                success: function(data) {
                    $('.loader').fadeOut();
                    if (data.message == 'success') {
                        $('#input-menu-name').val(data.data.menu_cd);
                        $('#input-menu-parent').val(data.data.menu_parent_id);
                        $('#input-menu-parent').trigger('change');
                        $('#input-menu-order').val(data.data.menu_order);
                        $('#input-menu-url').val(data.data.menu_url);
                        $('input[name=status_cd][value=' + data.data.status_cd + ']').prop("checked", true);

                    }
                }
            });
        });

        $('#btn-new-menu').click(function(e) {
            e.preventDefault();
            menu_id = null;

            $('#btn-update-menu').addClass('d-none');
            $('#btn-save-menu').removeClass('d-none');

            $('form').trigger("reset");
            $('#input-menu-parent').val("");
            $('#input-menu-parent').trigger('change');
            $('#input-menu-name').focus();
        });

        $('#btn-update-menu').click(function(e) {
            e.preventDefault();
            if (checkForm() > 0) {
                Swal.fire(
                    'Please fill this field!',
                    empty_field.join(", "),
                    'error'
                );
                return;
            }
            console.log(menu_id);
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
                        url: `${base_url}/app/menu/update`,
                        type: 'POST',
                        data: {
                            menu_id: menu_id,
                            data: $('form').serializeArray(),
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

        $('#btn-save-menu').click(function(e) {
            e.preventDefault();
            if (checkForm() > 0) {
                Swal.fire(
                    'Please fill this field!',
                    empty_field.join(", "),
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
                        url: `${base_url}/app/menu/add`,
                        type: 'POST',
                        data: {
                            data: $('form').serializeArray(),
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

    function checkForm() {
        empty_field = [];
        return $('form :input').filter(function() {
            if ($(this).val().length === 0) {
                if ($(this).attr("id")) {
                    var arr_input = ucwords($(this).attr("id").split("-").slice(1).join(" "));
                    console.log($(this).attr("id") + ' : ' + $(this).val().length);
                    empty_field.push(arr_input);
                    return $(this).val().length === 0;
                }
            }
        }).length;
    }
</script>
<?= $this->endSection(); ?>