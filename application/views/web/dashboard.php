<?php if ($this->session->flashdata('success')) { ?>

	<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">�</a>
		<strong><?php echo $this->session->flashdata('success'); ?></strong>
	</div>
	<?php $this->session->unset_userdata('success'); ?>
<?php } ?>

<?php if ($this->session->flashdata('error')) { ?>

	<div class="alert alert-danger">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">�</a>
		<strong><?php echo $this->session->flashdata('error'); ?></strong>
	</div>
	<?php $this->session->unset_userdata('error'); ?>
<?php } ?>
<style>
	.app-title {
		position: relative;
	}

	.ttl-btn {
		position: absolute;
		right: 18px;
		top: 2px;
		padding: 5px 36px;
		background-color: #79b6f7;
	}

	.img-btn {
		position: relative;

		width: 32px;
		height: 32px;
		right: 15%;
		border-radius: 8px;
		box-shadow: 0px 2px 7px 0px grey;

	}

	<?php if ($this->session->userdata('category_name') == 'Smart Class E-Book') : ?>.top-con,
	.bottom-con {
		visibility: hidden;
	}

	.supContent:hover .top-con {
		visibility: visible;
		animation: 500ms ease-in slidebottom;
	}

	.supContent:hover .bottom-con {
		visibility: visible;
		animation: 500ms ease-in slidetop;
	}

	@keyframes slidebottom {
		0% {
			transform: translateY(-5px);
		}
	}

	@keyframes slidetop {
		0% {
			transform: translateY(+5px);
		}
	}

	<?php endif; ?>
</style>
<main class="app-content">
	<div class="app-title">
		<ul class="app-breadcrumb breadcrumb m-0">
			<li class="breadcrumb-item"><?= $this->session->userdata('board_name') ?></li>
			<li class="breadcrumb-item"><?= $this->session->userdata('publication_name') ?></li>
			<?php if ($this->session->userdata('type') == 'Student') { ?>
				<li class="breadcrumb-item">Class <?= $this->session->userdata('classes') ?></li>
			<?php } else { ?>
				<li class="breadcrumb-item">Class <?= $this->session->userdata('classes') ?></li>
			<?php } ?>

			<li class="breadcrumb-item"><b><?= $this->session->userdata('category_name') ?></li></b></li>
			<input type="text" id="active" value="<?= $this->session->userdata('category') ?>" class="d-none">
		</ul>

		<?php $userdata = $this->session->userdata(); ?>
		<?php if (!$is_erp_login) : ?>
			<?php if ($userdata['type'] == 'Teacher') : ?>

				<a href="web/teacher_panel" class="btn btn-primary ttl-btn">
					<img src="<?= base_url('/images/test/minilms.png') ?>" alt="Teacher Image" class="img-btn">Teacher Section</a>
			<?php else : ?>
				<a href="web/student_panel" class="btn btn-primary ttl-btn">Student Section &nbsp </a>
			<?php endif; ?>
		<?php endif; ?>
		<?php /*
		//echo $userdata['user_id'];
		if ($userdata['user_id'] == 222 || $userdata['user_id'] == 246) {
			//print_r($userdata);
			if ($userdata['type'] == 'Teacher') {
		?>
				<a href="web/teacher_panel" class="btn btn-primary ttl-btn">Teacher Section</a>
			<?php } else { ?>
				<a href="web/student_panel" class="btn btn-primary ttl-btn">Student Section &nbsp </a>
		<?php }
		}*/ ?>
	</div>
	<form id="Selform" class="smooth-submit p-3" name="myformsearch" method="post" action="admin_master/default_product" novalidate>
		<div class="row justify-content-center">
			<div class="col-lg-3 pl-2 pr-3">
				<select id="select_board" class="col-lg-12 p-0 m-0 custom-select selectBoard_change" name="select_board" required="true">
					<?php //foreach ($board as $bo): 
					?>
					<option value="<?= $this->session->userdata('board_name') ?>" selected><?= $this->session->userdata('board_name') ?></option>
					<?php //endforeach; 
					?>
				</select>
			</div>
			<div class="col-lg-3 pl-2 pr-3">
				<select id="select_publication" class="col-lg-12 p-0 m-0 custom-select" name="select_publication" required="true">
					<?php foreach ($publication as $pub) : ?>
						<option value="<?= $pub->id ?>" selected><?= $pub->name ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php if ($this->session->userdata('type') == 'Student') { ?>
				<div class="col-lg-3 pl-2 pr-3">
					<select id="select_classes" class="col-lg-12 p-0 m-0 custom-select" name="select_classes" required="true">

						<option value="<?= $this->session->userdata('classes') ?>" selected><?= 'Class ' . $this->session->userdata('classes') ?></option>

					</select>
				</div>
			<?php } ?>

			<input type="text" class="d-none" value="<?= $this->session->userdata('msubject') ?>" id="msub_d" required="true" />
			<div class="col-lg-3 pl-2 pr-3">
				<select id="select_msubject" class="col-lg-12 p-0 m-0 custom-select <?= $this->session->userdata('type') == 'Teacher' ? 'teacher_series' : ''; ?>" name="select_msubject" required="true">


					<?php foreach ($msubjects as $cl) : ?>
						<?php if ($cl->id == $this->session->userdata('msubject')) : ?>
							<option value="<?= $cl->id ?>" selected><?= $cl->name ?></option>
						<?php else : ?>
							<option value="<?= $cl->id ?>"><?= $cl->name ?></option>
						<?php endif; ?>

					<?php endforeach; ?>

				</select>
			</div>
			<?php if ($this->session->userdata('type') == 'Teacher') : ?>
				<div class="col-lg-3 pl-2 pr-3">
					<select id="select_classes" class="col-lg-12 p-0 m-0 custom-select" name="select_classes" required="true">
						<?php /*foreach ($classesteacher as $cl) : ?>
							<option value="<?= $cl->id ?>" <?= $cl->id == $this->session->userdata('classes') ? ' selected="selected"' : ''; ?>><?= $cl->name ?></option>
						<?php endforeach; */ ?>
						<?php foreach ($selectable_classes as $cl) : ?>
							<option value="<?= $cl ?>" <?= $cl == $this->session->userdata('classes') ? ' selected="selected"' : ''; ?>>Class <?= $cl ?></option>
						<?php endforeach; ?>
					</select>
				</div>

			<?php endif; ?>

			<div class="col-lg-1 pl-2 pr-3">
				<input type="submit" name="submit" class="btn btn-success" value="Search">
			</div>

		</div>
	</form>

	<div class="row m-0 p-0">
		<div class="col-lg-3 p-2 m-0 home-side">
			<div class="iphone_frame">
				<div class="iphone_back_frame">
					<div class="wmain_sidebar">
						<ul class="category-list">
							<?php
							$userdata = $this->session->userdata();
							$current_subject = $this->session->userdata('msubject');
							$current_class = $this->session->userdata('classes');

							foreach ($category as $cat) {
								$has_entries = $this->db->where('msubject', $current_subject)
									->where('classes', $current_class)
									->where('type', $cat->id)
									->count_all_results('websupport');

								if ($cat->allow == 'Both' or $cat->allow == $userdata['type']) {
									if ($has_entries == 0 || $has_entries == null) {
										continue;
									}
							?>
									<li class="category-item" id="active<?= $cat->id ?>">
										<a tab_id="<?= $cat->id ?>" class="new-search">
											<img src="<?= base_url('images/category-icons/' . $cat->image) ?>" class="image_iphone_icon">
										</a>
										<p> <?= $cat->name ?> </p>
									</li>
							<?php
								}
							}
							?>
						</ul>
					</div>
				</div>

			</div>
		</div>



		<div class="col-lg-9 m-0 p-3">

			<div class="row">
				<table id="srch_tbl" class="table table-striped table-bordered" style="width:100%;float:left;">
					<thead style="display:none;">
						<tr>
							<th>Name</th>

						</tr>
					</thead>
					<tbody class="row">
						<?php

						if (empty($default)) { ?>
							<p class="text-danger m-3" style="
							font-weight: normal;
font-size: 20px;
width: 50%;
padding: 4rem 0;
line-height: 1.5;"> Uh Oh! &#x1F626; <br>The content you are looking for is either under process or not available for the selected criteria, please try choosing another class/book then press search.</p>




							<?php
						} else {
							foreach ($default as $def) :
							?>
								<tr class="col-lg-3 p-0 mt-1 mb-2 supContent">
									<td class="col-lg-12 p-0">
										<div class="col-lg-12">
											<a href="<?= $def->file_url ? $def->file_url : 'assets/files/' . $def->file_name ?>" class="p-0 m-0 digital-con" target="_blank">
												<div class="row m-0 p-0">
													<div class="col-lg-12 p-2 m-0 top-con">
														<h5>Click Here! For Download</h5>
													</div>
													<div class="col-lg-12 p-3 m-0 middle-con">
														<?php if (empty($def->book_image)) { ?>
															<img src="assets/img/3.png">
														<?php } else { ?>
															<img src="assets/bookicon/<?= $def->book_image ?>">
														<?php } ?>
													</div>
													<div class="col-lg-12 p-2 m-0 bottom-con">
														<h4><?= $def->title ?></h4>
														<h6>Class <?= $def->classes ?></h6>
													</div>

												</div>
											</a>

										</div>
										<?php if ($this->session->userdata('category_name') == 'Test Paper Generator') { ?>
											<!-- <div class="col-lg-10 p-2 m-auto" style="background: greenyellow; height: 42px; text-align: center; top: 7px; font-size: 14px;">-->

											<!--<a href="<?php //echo base_url(); 
																		?>query/teacher-question" target="_blank" style="color: #444;font-weight: 600;">Submit Your Question</a>-->

											<!--</div> -->
									</td>
								</tr>
							<?php } ?>
					<?php endforeach;
						}
					?>
					</tbody>
				</table>
			</div>

			<!--<div class="row m-0">
			<?php if (empty($default)) { ?>
				<p class="text-danger" style="font-size: 30px"> Coming Soon.....</p>
			<?php
			} else {
				foreach ($default as $def) :
			?>
					<div class="col-lg-3 p-2 m-0">
						<a href="assets/files/<?= $def->file_name ?>" class="p-0 m-0 digital-con" target="_blank">
							<div class="row m-0 p-0">
								<div class="col-lg-12 p-2 m-0 top-con">
									<h5>Click Here! For Download</h5>
								</div>
								<div class="col-lg-12 p-3 m-0 middle-con">
									<img src="assets/img/download2.png">
								</div>
								<div class="col-lg-12 p-2 m-0 bottom-con"> 
									<h4><?= $def->title ?></h4> 
									<h6>Class <?= $def->classes ?></h6>
								</div>
								
							</div> 
						</a>
						
					</div>
					<?php if ($this->session->userdata('category_name') == 'Test Paper Generator') { ?>
					<div class="col-lg-2 p-2 m-0" style="background: greenyellow; height: 42px; text-align: center; top: 7px; font-size: 14px;">
					
					<a href="<?php echo base_url(); ?>query/teacher-question" target="_blank" style="color: #444;font-weight: 600;">Submit Your Question</a>
					
					</div>
					<?php } ?>
				<?php endforeach;
			}
				?>
		</div>-->
		</div>
	</div>
</main>
<script>
	$('.teacher_series').on('change', function(e) {
		const valueSelected = $(this).val();
		const url = `<?= base_url() ?>/admin_master/getClasses/${valueSelected}`;
		// $('#select_classes').html('<option value="" disabled selected>Select Class</option>')
		// fetch(url).then(res => res.json()).then(data => console.log(data));
		fetch(url).then(res => res.json()).then(data => {
			let classOptions = '';
			data.forEach((item, key, arr) => {
				classOptions += `<option value="${item.id}">${item.name}</option>`;
			});
			$('#select_classes').html(classOptions);
		});
	});
</script>
<script>

</script>