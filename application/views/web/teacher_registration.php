<style>
	.form-wrapper {
		max-width: 800px;
		margin: 40px auto;
	}

	.form-wrapper .card .card-heading {
		padding: 20px 0;
		background: #1a1a1a;
		-webkit-border-top-left-radius: 10px;
		-moz-border-radius-topleft: 10px;
		border-top-left-radius: 10px;
		-webkit-border-top-right-radius: 10px;
		-moz-border-radius-topright: 10px;
		border-top-right-radius: 10px;
		color: #fff;
	}

	.form-wrapper .title {
		font-size: 24px;
		text-transform: uppercase;
		font-weight: 700;
		text-align: center;
		color: #fff;
	}

	.form-wrapper .form-control {
		background: #f4f4f4;
		min-height: 42px;
		font-size: 15px;
	}

	.form-wrapper select.form-control:not([size]):not([multiple]) {
		min-height: 42px;
	}

	.form-wrapper .card-footer {
		background-color: transparent;
	}

	.form-wrapper .btn--red {
		background: #ff4b5a;
		border-radius: 5px;
		line-height: 50px;
		padding: 0 40px;
		border-color: #ff4b5a;
	}

	.series-select {
		border-bottom: none;
		border-radius: 5px 5px 0 0;
	}

	.series-classes-container {
		border: 1px solid #ced4da;
		border-top: none;
		border-radius: 0 0 5px 5px;
		color: #495057;
		background: #f4f4f4;
		font-size: 15px;
		min-height: 42px;
	}
</style>
<div class="form-wrapper">
	<div class="card">
		<div class="card-heading">
			<h2 class="title">New Teacher Register</h2>
		</div>
		<div class="card-body">
			<form id="teacherRegForm" method="post" action="<?php echo base_url(); ?>admin_master/add_teacher_custom">
				<div class="form-body">
					<div class="row m-0 p-2">
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_name">Full Name *</label>
								<input type="text" class="form-control" id="teacher_name" name="name" required="true">
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_mobile">Mobile *</label>
								<input type="text" class="form-control" id="teacher_mobile" name="mobile" pattern="[1-9]{1}[0-9]{9}" title="10 digit Mobile number" required="true">
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_email">Email(School) *</label>
								<input type="email" class="form-control" id="teacher_email" name="email" required="true" placeholder="This will be used for login">
								<div id="getemail_descc"></div>
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_password">Create Your Password*</label>
								<input type="password" class="form-control" id="teacher_password" name="password" pattern=".{8,}" title="Must contain at least 8 or more characters" required="true">
								<div id="getemail_desc"></div>
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_pin">Pin Code *</label>
								<input type="text" class="form-control" id="teacher_pin" pattern="[0-9]{6}" title="Six digit zip code" name="pin" required="true">
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="school_nameT">School Name *</label>
								<input type="text" class="form-control" id="school_nameT" name="school_name" required="true">
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_address">Address(School) *</label>
								<textarea class="form-control" id="teacher_address" name="address" required="true"></textarea>
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_addresss2">Address(Personal)</label>
								<textarea class="form-control" id="teacher_addresss2" name="addresss"></textarea>
							</div>
						</div>
					</div>

					<div class="row m-0 p-2">
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="principal_name">Principal's Name *</label>
								<input type="text" class="form-control" id="principal_name" name="principal_name" required="true">
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="country_type">Country *</label>
								<select class="form-control" name="country_type" id="country_type" required="true">
									<option value="">--Select country--</option>
									<?php foreach ($country as $couy) : ?>
										<option value="<?= $couy->id ?>"><?= $couy->name ?></option>
									<?php endforeach; ?>
								</select>

							</div>
						</div>
					</div>

					<div class="row m-0 p-2" id="Others">
						<div class="col-lg-4 p-2">
							<div class="form-group">
								<label for="oth_country">Country-Name</label>
								<input type="text" class="form-control" id="oth_country" name="oth_country">
							</div>
						</div>
						<div class="col-lg-4 p-2">
							<div class="form-group">
								<label for="oth_state">State *</label>
								<input type="text" class="form-control" id="oth_state" name="oth_state">
							</div>
						</div>
						<div class="col-lg-4 p-2">
							<div class="form-group">
								<label for="oth_city">City *</label>
								<input type="text" class="form-control" id="oth_city" name="oth_city">
							</div>
						</div>
					</div>

					<div class="row m-0 p-2" id="India">
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_state1">State *</label>
								<select class="form-control" name="state" id="state">
								</select>
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_city1">City *</label>
								<select class="form-control" name="city" id="city">
								</select>
							</div>
						</div>
					</div>
					<div class="row m-0 p-2">
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_dob">DOB</label>
								<input type="date" class="form-control" id="teacher_dob" name="dob">
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_emails">Email(Personal)</label>
								<input type="text" class="form-control" id="teacher_emails" name="emailss">
							</div>
						</div>
					</div>

					<div class="row m-0 p-2">
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="session_slot1">Session Start *</label>
								<select class="form-control" name="session_start" id="session_start" required="true">
									<option value="">--Select Slot--</option>
									<option value='1'>January</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
								</select>
							</div>
						</div>

						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="session_slot1">Board *</label>
								<select class="form-control" name="board" id="board" required="true">
									<option value="">Select</option>
									<?php foreach ($board as $cou) : ?>
										<option value="<?= $cou->name ?>"><?= $cou->name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>



					</div>

					<!--<div class="row m-0 p-2">
								
                        </div>-->

					<!-- <div class="row m-0 p-2">
						<div class="col-lg-12 p-2">
							<div class="form-group">
								<label>Series *</label>
								<div class="row" id="ser_section">

									<p class="text-danger">Select board</p>
								</div>
							</div>
						</div>
					</div> -->

					<div id="seriesClassesContainer" class="row m-0 p-2">
						<div class="col-lg-12 p-2">
							<select class="form-control series-select" id="series" required>
								<option value="" disabled selected>Select Series (Please select your board first)</option>
							</select>
						</div>
					</div>



					<div class="row m-0 p-2">
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="referrel_name">Orange Representative’s Name *</label>
								<input type="text" class="form-control" id="referrel_name" name="referrel_name" required="true">
							</div>
						</div>
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="referrel_mobile">Orange Representative’s Contact *</label>
								<input type="text" class="form-control" id="referrel_mobile" pattern="[1-9]{1}[0-9]{9}" title="10 digit Mobile number" name="referrel_mobile" required="true">
							</div>
						</div>

					</div>


				</div>
				<div class="card-footer">
					<button class="btn btn-primary btn--red float-left">Register</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	(function() {
		const form = document.querySelector('#teacherRegForm');
		const checkboxes = form.querySelectorAll('input[type=checkbox]');
		const checkboxLength = checkboxes.length;
		const firstCheckbox = checkboxLength > 0 ? checkboxes[0] : null;

		function init() {
			if (firstCheckbox) {
				for (let i = 0; i < checkboxLength; i++) {
					checkboxes[i].addEventListener('change', checkValidity);
				}

				checkValidity();
			}
		}

		function isChecked() {
			for (let i = 0; i < checkboxLength; i++) {
				if (checkboxes[i].checked) return true;
			}

			return false;
		}

		function checkValidity() {
			const errorMessage = !isChecked() ? 'Choose at least one class.' : '';
			firstCheckbox.setCustomValidity(errorMessage);
		}

		init();
	})();
</script>