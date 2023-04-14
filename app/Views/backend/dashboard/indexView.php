<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid mt-3">

		<!-- Token -->
		<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
		
		<div class="row">
			<div class="col-md-12">
				<div id="showData"></div>
			</div>
			<div class="col-md-2 offset-md-5">
				<button class="btn btn-success btn-block basicStats">
					Refresh
				</button>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>