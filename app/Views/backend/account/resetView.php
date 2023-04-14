<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid mt-3">
		<div class="row">
			<div class="col-md-2">
				<?= $this->include('backend/account/partials/leftMenuView'); ?>
			</div>
			<div class="col-md-8 offset-md-1">
				<div id="showData">
					<?= $this->include('backend/account/partials/resetView'); ?>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>