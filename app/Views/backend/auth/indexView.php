<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid mt-3">
		<div class="row">
			<div class="col-md-12">
				<div id="showData">
					<?= $this->include('backend/auth/partials/indexView'); ?>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>