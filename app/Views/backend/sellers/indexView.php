<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div id="showData">
					<?= $this->include('backend/sellers/partials/indexView'); ?>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>