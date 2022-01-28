<?= $this->extend('themes/theme'); ?>
<?= $this->section('content'); ?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="section-title fix-title">Management User</h2>
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
                    <div class="card-header">
                        <div class="col-12 p-0">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-lg-1 col-md-3 col-sm-3 col-form-label">Search</label>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <input type="text" class="form-control rounded-0" id="autocomplete" placeholder="Search employee by ext id or name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-title fix-title">Profile Access</div>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-7 col-md-9 col-sm-9">
                                        <div class="form-group">
                                            <label for="input-username">Username</label>
                                            <div class="input-group mb-3">
                                                <input type="text" value="<?= $profile_access[0]["user_name"] ?>" class="form-control" placeholder="" aria-label="" disabled>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary action-access-button" data-action="reset_account" type="button">Reset Account</button>
                                                    <button class="btn btn-outline-primary action-access-button" data-action="deactive_account" type="button">De-Active Account</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="control-label">Status</div>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="status_user" value="active" class="selectgroup-input" <?= $profile_access[0]["user_status"] == "active" ? "checked" : "" ?> disabled>
                                                    <span class="selectgroup-button">Active</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="status_user" value="inactive" class="selectgroup-input" <?= $profile_access[0]["user_status"] == "inactive" ? "checked" : "" ?> disabled>
                                                    <span class="selectgroup-button">Inactive</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="status_user" value="blocked" class="selectgroup-input" <?= $profile_access[0]["user_status"] == "blocked" ? "checked" : "" ?> disabled>
                                                    <span class="selectgroup-button">Blocked</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="section-title fix-title">Group Access</div>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <form id="group">
                                            <div class="row">
                                                <?php if (count($group_access) > 0) : ?>
                                                    <?php foreach ($group_access as $key => $value) : ?>
                                                        <div class="form-group col-lg-4 col-md-6 col-sm-6">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="group_access" value="<?= $value['group_access_menu_id'] ?>" class="custom-control-input" id="group-access-<?= $value['group_access_menu_id'] ?>">
                                                                <label class="custom-control-label" for="group-access-<?= $value['group_access_menu_id'] ?>"><?= $value['group_cd'] ?></label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <select id="app-group-access" class="form-control select2">
                                                <option></option>
                                                <?php if (count($list_group) > 0) : ?>
                                                    <?php foreach ($list_group as $key => $value) : ?>
                                                        <option value="<?= $value['group_id'] ?>"><?= $value['group_cd'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-6">
                                                <button class="btn btn-outline-primary w-100 action-access-button" data-action="add_group" type="button">Add</button>
                                            </div>
                                            <div class="form-group col-6">
                                                <button class="btn btn-outline-primary w-100 action-access-button" data-action="remove_group" type="button">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="section-title fix-title">Role Access</div>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <form id="role">
                                            <div class="row">
                                                <?php if (count($role_access) > 0) : ?>
                                                    <?php foreach ($role_access as $key => $value) : ?>
                                                        <div class="form-group col-lg-4 col-md-6 col-sm-6">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="role_access" value="<?= $value['role_access_id'] ?>" class="custom-control-input" id="role-access-<?= $value['role_access_id'] ?>">
                                                                <label class="custom-control-label" for="role-access-<?= $value['role_access_id'] ?>"><?= $value['role_cd'] ?></label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <select id="app-role-access" class="form-control select2">
                                                <option></option>
                                                <?php if (count($list_role) > 0) : ?>
                                                    <?php foreach ($list_role as $key => $value) : ?>
                                                        <option value="<?= $value['role_id'] ?>"><?= $value['role_cd'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-6">
                                                <button class="btn btn-outline-primary w-100 action-access-button" data-action="add_role" type="button">Add</button>
                                            </div>
                                            <div class="form-group col-6">
                                                <button class="btn btn-outline-primary w-100 action-access-button" data-action="remove_role" type="button">Remove</button>
                                            </div>
                                        </div>
                                    </div>
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
    $(document).ready(function() {
        $('.loader').fadeOut();
        init_ajax();
        $("#app-group-access").select2({
            placeholder: "Group Access",
        });

        $("#app-role-access").select2({
            placeholder: "Role Access",
        });

        $('#autocomplete').autocomplete({
            minChars: 3,
            paramName: 'search_string',
            serviceUrl: `${base_url}/app/employee/search`,
            beforeRender: function(container, suggestions) {
                suggestions.forEach((element) => {
                    let idx = suggestions.indexOf(element);
                    let el = '<div class="col-6">' + element["options"]['ext_id'] + '</div><div class="col-6 text-right">' + [element["options"]['departement'], element["options"]['job']].join(" - ") + '</div>';

                    $('.autocomplete-suggestion[data-index="' + idx + '"]').append(`<div class='options row'></div>`);
                    $('.autocomplete-suggestion[data-index="' + idx + '"]').find(".options").html(el);
                });
            },
            onSelect: function(suggestion) {
                $.ajax({
                    url: `${base_url}/app/user/search`,
                    type: 'GET',
                    data: {
                        user_id: suggestion.data,
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.message == 'success') {
                            location.reload();
                        }

                    }
                });
            }
        });

        $('.action-access-button').click(function(e) {
            e.preventDefault();
            let action = $(this).attr("data-action");
            let data = null;

            if (action == 'add_group') {
                data = $('#app-group-access').val();

            }

            if (action == 'remove_group') {
                data = $('form#group').serializeArray();
            }

            if (action == 'add_role') {
                data = $("#app-role-access").val();

            }

            if (action == 'remove_role') {
                data = $('form#role').serializeArray();
            }

            console.log(action);
            console.log(data);

            Swal.fire({
                title: 'Area you sure want to save changes?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.value) {
                    $('.loader').fadeIn();
                    $.ajax({
                        url: `${base_url}/app/user/access`,
                        type: 'POST',
                        data: {
                            action: action,
                            data: data,
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            console.log(data);
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
                                    'Error : ' + data.message,
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