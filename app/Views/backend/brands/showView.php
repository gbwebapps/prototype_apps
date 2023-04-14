<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid">

		<!-- Token -->
		<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
		
		<div class="row">
			<div class="col-md-12">
				<div id="showData">
					<?= $this->include('backend/brands/partials/showView'); ?>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>