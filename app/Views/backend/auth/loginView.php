<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid h-100">
		<div class="row align-items-center h-100">
			<div class="col-md-4 offset-md-4">
				<div id="showData">
					<?= $this->include('backend/auth/partials/loginView'); ?>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>