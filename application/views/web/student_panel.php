<style>
	.app-title {
		background-color: #ddd;
		color: #fff;
		padding: 20px 0;
		margin-bottom: 30px;
		border-radius: 5px;
	}

	.app-title h3 {
		margin: 0;
		font-weight: bold;
	}

	.test-card {
		transition: transform 0.2s;
	}

	.test-card:hover {
		transform: scale(1.05);
	}
</style>

<main class="app-content">
	<div class="app-title sp-header-section">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					<h3 class="teacher-ttl">Student Panel</h3>
				</div>
				<div class="col-lg-5">
					<!-- You can add something here if needed -->
				</div>
				<div class="col-lg-4">
					<h3 class="teacher-ttl">Teacher Code - <?= $user[0]->stu_teacher_id ?></h3>
				</div>
			</div>
		</div>
	</div>


	<?php if ($this->session->flashdata('error')) : ?>
		<p class="alert alert-danger text-center"><strong><?= $this->session->flashdata('error'); ?></strong></p>
		<?php $this->session->unset_userdata('error'); ?>
	<?php endif; ?>

	<div class="inner-section">
		<section class="py-5">
			<div class="container py-5">
				<div class="row justify-content-center">
					<?php if ($tests) : ?>
						<?php foreach ($tests as $key => $test) : ?>
							<?php
							// Determine if the test can be attempted and set button classes accordingly.
							$disabled   = !$test['status']['can_attempt'] ? 'disabled' : '';
							$btnClass   = $test['status']['can_attempt'] ? 'btn-success' : 'btn-secondary';
							$testType   = $test['paper_mode'] < 20 ? 'Objective' : 'Subjective';
							$actionUrl  = $test['paper_mode'] < 20 ? base_url() . 'web/objective_paper' : base_url() . 'web/subjective_paper';
							?>
							<div class="col-md-4 mb-4">
								<div class="card test-card shadow">
									<div class="card-body text-center">
										<h5 class="card-title"><?= $testType ?> Test <?= ($key + 1) ?></h5>
										<form action="<?= $actionUrl ?>" method="post">
											<input type="hidden" name="paper_mode" value="<?= $test['paper_mode'] ?>">
											<button type="submit" class="btn <?= $btnClass ?> btn-lg m-2" <?= $disabled ?>>
												<?= $testType ?> Test <?= ($key + 1) ?>
											</button>
										</form>
										<p class="card-text text-muted"><?= $test['status']['reason'] ?></p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<p class="alert alert-info text-center">No tests have been assigned yet.</p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</div>
</main>