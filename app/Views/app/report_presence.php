<?= $this->extend('themes/theme'); ?>
<?= $this->section('content'); ?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="section-title fix-title">Presence Report</h2>
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
                        <div class="col-6 p-0">
                            <div class="form-group row">
                                <label for="datepicker" class="col-lg-2 col-md-3 col-sm-3 col-form-label">Period</label>
                                <div class="col-lg-10 col-md-9 col-sm-9">
                                    <input type="text" class="form-control form-control-sm text-date" name="datepicker" id="datepicker" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-6 p-0 d-flex align-items-end flex-column">
                            <div class="buttons">
                                <button class="btn btn-icon icon-left btn-light" onclick="export_to_excel()"><i class="fas fa-file-excel"></i>Export</button>
                                <button class="btn btn-icon icon-left btn-light" onclick="printDiv('data-container')"><i class="fas fa-print"></i>Print</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-lg-2 col-md-1 col-sm-1">
                                                <span class="colorinput-color marked-as-dayoff"></span>
                                            </div>
                                            <label class="col-lg-10 col-md-11 col-sm-11 col-form-label">Day Off / Public Holiday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-lg-2 col-md-1 col-sm-1">
                                                <span class="colorinput-color marked-as-absent"></span>
                                            </div>
                                            <label class="col-lg-10 col-md-11 col-sm-11 col-form-label">Absent</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-lg-2 col-md-1 col-sm-1">
                                                <span class="colorinput-color marked-as-nc"></span>
                                            </div>
                                            <label class="col-lg-10 col-md-11 col-sm-11 col-form-label">Not Clock In/ Clock Out</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div id="data-container"></div>
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
    let datepicker = null;
    $(document).ready(function() {
        $('.loader').fadeOut();
        init_ajax();
        let date = moment().format('YYYY-MM-DD')
        $.when(init_datepicker()).done(function(e) {
            get_presence_data_by_month(date);

            datepicker.on("changeMonth", function(e) {
                date = moment(e.date).format('YYYY-MM-DD');
                get_presence_data_by_month(date);
            });
        });


    });

    function init_datepicker() {
        datepicker = $("#datepicker").datepicker({
            format: "MM yyyy",
            startView: "months",
            minViewMode: "months",
            autoclose: true,
        }).datepicker("setDate", 'now');
    }

    function get_presence_data_by_month(date) {
        $('.loader').fadeIn();
        console.log(date);
        $.ajax({
            url: `${base_url}/app/report/data-presence`,
            type: 'GET',
            data: {
                date: date
            },
            dataType: 'HTML',
            success: function(data) {
                $('#data-container').html(data);
                init_freeze_table();

                $('.loader').fadeOut();
            }
        });
    }

    function init_freeze_table() {
        $("#data-container").freezeTable({
            'columnNum': 3,
            'columnKeep': true,
        });

        $('td.cell-time').each(function(e) {
            var marked_as = $(this).text().trim();

            if (marked_as == "A") {
                $(this).addClass('marked-as-absent');
            } else if (marked_as == "00:00") {
                $(this).addClass('marked-as-nc');
            }
        });

        $('.cell-month-name').html($('#datepicker').val());
    }

    function export_to_excel() {
        var filename = 'Presence Report ' + $('#datepicker').val();
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById('table-report-presence');

        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement('a');
        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
<?= $this->endSection(); ?>