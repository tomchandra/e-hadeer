<?= $this->extend('themes/theme'); ?>
<?= $this->section('content'); ?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="section-title fix-title">Management Employee</h2>
                    </div>
                    <div class="card-footer bg-whitesmoke pt-3 pb-2 d-flex align-items-end flex-column">

                    </div>
                </div>
            </div>
        </div>
        <!-- FORM ADD -->
        <div class="row">
            <div class="col-lg-12">
                <div id="card-form-add" class="card card-info" style="display: none;">
                    <div class="card-header">
                        <h4>Add Employee</h4>
                    </div>
                    <form>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-ext-id">Ext ID</label>
                                        <input type="text" name="person_ext_id" class="form-control" id="input-ext-id" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-name">Name</label>
                                        <input type="text" name="person_name" class="form-control" id="input-name" required>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <label for="input-date-birth">Datebirth</label>
                                            <input type="date" name="birth_dttm" class="form-control" id="input-date-birth" required>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="input-gender">Gender</label>
                                            <select name="gender_cd" id="input-gender" class="form-control" required>
                                                <option value=""></option>
                                                <option value="m">Male</option>
                                                <option value="f">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <label for="input-phone">Phone</label>
                                            <input type="text" name="cellphone" class="form-control" id="input-phone" required>
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="input-email">Email</label>
                                            <input type="email" name="email" class="form-control" id="input-email" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-address">Address</label>
                                        <textarea name="address" class="form-control h-100" id="input-address" required></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-joindate">Join Date</label>
                                        <input type="date" name="join_dttm" class="form-control" id="input-join-date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-departement">Departement</label>
                                        <select name="departement_id" id="input-departement" class="form-control" required>
                                            <option value=""></option>
                                            <?php if (count($departement) > 0) : ?>
                                                <?php foreach ($departement as $key => $value) : ?>
                                                    <option value="<?= $value['departement_id'] ?>"><?= $value['departement_cd'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-job">Job</label>
                                        <select name="job_id" id="input-job" class="form-control" required>
                                            <option value=""></option>
                                            <?php if (count($job) > 0) : ?>
                                                <?php foreach ($job as $key => $value) : ?>
                                                    <option value="<?= $value['job_id'] ?>"><?= $value['job_cd'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer bg-whitesmoke d-flex align-items-end flex-column">
                        <div class="buttons">
                            <button id="btn-save-employee" class="btn btn-sm btn-outline-primary d-none">Save</button>
                            <button id="btn-update-employee" class="btn btn-sm btn-outline-primary d-none">Update</button>
                            <button id="btn-cancel-employee" class="btn btn-sm btn-outline-danger">Cancel</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END FORM ADD -->

        <!-- DATA LIST -->
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">

                        <div class="col-3 p-0">
                            <span id="app-search"></span>
                        </div>
                        <div class="col-9 p-0 d-flex align-items-end flex-column">
                            <div class="buttons">
                                <button id="btn-add-employee" class="btn btn-outline-primary note-btn">Add</button>
                                <button id="btn-edit-employee" class="btn btn-outline-primary note-btn">Edit</button>
                                <button id="btn-delete-employee" class="btn btn-outline-primary note-btn">Delete</button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div style="overflow: scroll;">
                            <table id="tbl-employee" class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Ext ID</th>
                                        <th>Name</th>
                                        <th>Birthdate</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Join Date</th>
                                        <th>Departement</th>
                                        <th>Position</th>
                                        <th>Departement ID</th>
                                        <th>Position ID</th>
                                        <th>Gender ID</th>
                                        <th>User ID</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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
    let empty_field = [];
    let person_ext_id = null;
    let arr_column = ["person_ext_id", "person_name", "birth_dttm", "gender_nm", "cellphone", "email", "address", "join_dttm", "departement_cd", "job_cd", "departement_id", "job_id", "gender_cd", "user_id"];
    var columns = [];
    var columnDefs = [];
    var i = 0;
    arr_column.forEach(element => {
        var is_visible = "";
        if (element == 'departement_id' || element == 'job_id' || element == 'gender_cd' || element == 'user_id') {
            is_visible = "hide_column";
        }
        columns.push({
            "data": element,
            "sClass": is_visible,
        });
    });

    $(document).ready(function() {
        $('.loader').fadeOut();
        init_ajax();
        init_datatable();

        $('#btn-add-employee').click(function() {
            $('#btn-save-employee').removeClass("d-none");
            $('#btn-update-employee').addClass("d-none");
            $('form').trigger("reset");
            $('#input-extid').attr("readonly", false);

            let display = $('#card-form-add').css("display");
            if (display == 'none') {
                $('#card-form-add').slideDown();
            } else {
                $('#card-form-add').slideUp();
            }
        });

        $("#tbl-employee").delegate("tbody tr", "click", function() {
            $('#card-form-add').slideUp();
            $('form').trigger("reset");
            person_ext_id = $(this).find('td').eq(0).text().trim();

            $(this).find('td').each(function() {
                var defs = $(this).attr("data-defs");
                var defs_text = $(this).text().trim();

                $('[name=' + defs + ']').val(defs_text);
                console.log(defs);

            })
        });

        $('#btn-edit-employee').click(function(e) {
            e.preventDefault();
            $('#btn-save-employee').addClass("d-none");
            $('#btn-update-employee').removeClass("d-none");

            if (person_ext_id == null || person_ext_id == '') {
                Swal.fire(
                    'Error!',
                    'Please choose data on table to edit!',
                    'warning'
                );
            } else {
                $('#input-extid').attr("readonly", true);
                $('#card-form-add').slideDown();
            }

        });

        $('#btn-save-employee').click(function(e) {
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
                        url: `${base_url}/app/employee/add`,
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
                                );
                                location.reload();
                            } else if (data.message == 'duplicate') {
                                Swal.fire(
                                    'Data not saved!',
                                    'Ext Id already exist',
                                    'error'
                                );
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

        $('#btn-update-employee').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Area you sure ?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `${base_url}/app/employee/update`,
                        type: 'POST',
                        data: {
                            data: $('form').serializeArray(),
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.message == 'success') {
                                Swal.fire(
                                    'Success!',
                                    'Data saved!',
                                    'success'
                                );
                                location.reload();
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

        $('#btn-delete-employee').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Area you sure ?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.value) {
                    if (person_ext_id == null || person_ext_id == '') {
                        Swal.fire(
                            'Error!',
                            'Please choose data on table to delete!',
                            'warning'
                        );
                    } else {
                        $.ajax({
                            url: `${base_url}/app/employee/delete`,
                            type: 'POST',
                            data: {
                                id: person_ext_id,
                            },
                            dataType: 'JSON',
                            success: function(data) {
                                if (data.message == 'success') {
                                    Swal.fire(
                                        'Success!',
                                        'Data deleted!',
                                        'success'
                                    );
                                    location.reload();
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
                }
            });
        });

        $('#btn-cancel-employee').click(function(e) {
            e.preventDefault();
            $('form').trigger("reset");
            $('#card-form-add').slideUp();
        });
    });

    function init_datatable() {
        $('#app-search').empty();
        $('#tbl-employee').dataTable({
            "destroy": true,
            "ajax": `${base_url}/app/employee/list`,
            "columns": columns,
            "createdRow": function(row, data, dataIndex) {
                for (var i = 0; i < arr_column.length; i++) {
                    $(row).find('td:eq(' + i + ')').attr('data-defs', arr_column[i]);
                }
            },
        });

        $('.dataTables_filter').find('input').attr("placeholder", "Type here to search").detach().appendTo('#app-search');
    }

    function checkForm() {
        empty_field = [];
        return $('form :input').filter(function() {
            if ($(this).val().length === 0) {
                if ($(this).attr("id")) {
                    var arr_input = ucwords($(this).attr("id").split("-").slice(1).join(" "));
                    empty_field.push(arr_input);
                    return $(this).val().length === 0;
                }
            }
        }).length;
    }
</script>
<?= $this->endSection(); ?>