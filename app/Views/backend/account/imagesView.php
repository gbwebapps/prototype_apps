<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid mt-3">

		<!-- Token -->
		<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

		<div class="row">
			<div class="col-md-2">
				<?= $this->include('backend/account/partials/leftMenuView'); ?>
			</div>
			<div class="col-md-8 offset-md-1">
				<div id="showData">
					<?= $this->include('backend/account/partials/imagesView'); ?>
				</div>
			</div>
		</div>
	</div>

	<div id="id" data-value="<?= esc($currentSeller->identity->id); ?>">

<?= $this->endSection(); ?>