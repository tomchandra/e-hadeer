<?= $this->extend('themes/theme'); ?>
<?= $this->section('content'); ?>
<div class="modal fade show" tabindex="-1" role="dialog" id="camera-modal" style="display: none;">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document" style="width: 370px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" style="background-color: black;">
				<div id="my_camera" style="margin: 0 auto;"></div>
				<div id="results" style="margin: 0 auto;"></div>
			</div>
			<div class="modal-footer bg-whitesmoke br">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="btn-save-presence" type="button" class="btn btn-primary">Take Picture</button>
			</div>
		</div>
	</div>
</div>

<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="card">
									<div class="card-body" style="background-color: #ecf0f1;">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 text-center">
												<?= date("D, d F Y"); ?>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12">
												<div class="d-flex justify-content-center align-items-center">
													<div class="clock__time"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 buttons">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<button id="clock-in" data-type="clock_in" class="btn btn-lg btn-primary w-100 presence">Clock In <?= $clock_in ?></button>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<button id="clock-out" data-type="clock_out" class="btn btn-lg btn-primary w-100 presence">Clock Out <?= $clock_out ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						<div class="section-title mt-0">Presence History</div>
						<div class="row">
							<div class="col-12 mb-3">
								<div class="row">
									<div class="col-lg-3 col-md-12 col-sm-12">
										<div class="form-group row">
											<div class="col-lg-2 col-md-1 col-sm-1">
												<span class="colorinput-color bg-secondary"></span>
											</div>
											<label class="col-lg-10 col-md-11 col-sm-11 col-form-label">Day Off / Public Holiday</label>
										</div>
									</div>
									<div class="col-lg-3 col-md-12 col-sm-12">
										<div class="form-group row">
											<div class="col-lg-2 col-md-1 col-sm-1">
												<span class="colorinput-color bg-warning"></span>
											</div>
											<label class="col-lg-10 col-md-11 col-sm-11 col-form-label">Late Work / Early Home</label>
										</div>
									</div>
									<div class="col-lg-3 col-md-12 col-sm-12">
										<div class="form-group row">
											<div class="col-lg-2 col-md-1 col-sm-1">
												<span class="colorinput-color bg-danger"></span>
											</div>
											<label class="col-lg-10 col-md-11 col-sm-11 col-form-label">Not Clock In/ Clock Out</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 mb-3">
								<div class="row">
									<div class="col-6">
										<div class="form-group row">
											<label for="datepicker" class="col-lg-2 col-md-3 col-sm-3 col-form-label">Period</label>
											<div class="col-lg-10 col-md-9 col-sm-9">
												<input type="text" class="form-control form-control-sm text-date" name="datepicker" id="datepicker" readonly />
											</div>
										</div>
									</div>
									<div class="col-6 text-right">
										<button class="btn btn-icon icon-left btn-light" onclick="printDiv('data-container')"><i class="fas fa-print"></i>Print</button>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div id="data-container"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?= $this->endSection(); ?>

<?= $this->section('extra-js'); ?>
<script>
	let presence_type = null;
	$(document).ready(function() {
		init_ajax();
		$('.loader').fadeOut();

		let date = moment().format('YYYY-MM-DD')
		$.when(init_datepicker()).done(function(e) {
			get_presence_data_by_user(date);

			datepicker.on("changeMonth", function(e) {
				date = moment(e.date).format('YYYY-MM-DD');
				get_presence_data_by_user(date);
			});
		});

		$('.presence').click(function(e) {
			e.preventDefault();
			$('#camera-modal').modal('show');
			$('#my_camera').show();
			$('#results').empty();
			$(this).addClass('btn-progress');
			presence_type = $(this).attr("data-type");

			Webcam.set({
				width: 320,
				height: 240,
				image_format: 'jpeg',
				jpeg_quality: 90
			});

			Webcam.attach('#my_camera');
		});

		$('#btn-save-presence').click(function() {
			upload().then(function(res, rej) {
				if (res == 200) {
					$.ajax({
						url: `${base_url}/app/presence/add`,
						type: 'POST',
						data: {
							presence_type: presence_type
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

		$('#camera-modal').on('hidden.bs.modal', function() {
			Webcam.reset();
		});

	});

	async function upload() {
		return new Promise(function(res, rej) {
			Webcam.snap(function(data_uri) {
				$('#results').html('<img id="imageprev" src="' + data_uri + '"/>');
				$('#my_camera').hide();
				$('.presence').removeClass('btn-progress');
				$('.loader').fadeIn();
				if (data_uri) {
					Webcam.upload(data_uri, `${base_url}/app/presence/upload-image-presence`, function(code, text) {
						$('#camera-modal').modal('hide');
						res(code);
					});
				}
			});
			Webcam.reset();
		});
	}

	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;

		window.print();

		document.body.innerHTML = originalContents;
	}

	function init_datepicker() {
		datepicker = $("#datepicker").datepicker({
			format: "MM yyyy",
			startView: "months",
			minViewMode: "months",
			autoclose: true,
		}).datepicker("setDate", 'now');
	}

	function get_presence_data_by_user(date) {
		$('.loader').fadeIn();
		console.log(date);
		$.ajax({
			url: `${base_url}/app/presence/data-presence`,
			type: 'GET',
			data: {
				date: date
			},
			dataType: 'HTML',
			success: function(data) {
				$('#data-container').html(data);
				$('.loader').fadeOut();
			}
		});
	}
</script>
<?= $this->endSection(); ?>