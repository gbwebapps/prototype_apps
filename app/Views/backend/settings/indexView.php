<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>

	<!-- Token -->
	<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
	
	<div class="container mt-3">
		<div class="row">
			<div class="col-md-12">
				<div class="accordion" id="accordionBackend">
				    <?php foreach($sections as $section): ?>
				        <div class="card">
				            <div class="card-header" id="heading<?= esc(ucfirst($section['name'])); ?>">
				                <h5 class="mb-0">
				                    <a class="text-left openCard" href="#" data-toggle="collapse" data-target="#collapse<?= esc(ucfirst($section['name'])); ?>" aria-expanded="true" aria-controls="collapse<?= esc(ucfirst($section['name'])); ?>" data-section="<?= esc($section['name']); ?>">
				                        <i class="<?= esc($section['icon']); ?>"></i> <?= esc(ucfirst($section['name'])); ?>
				                    </a>
				                </h5>
				            </div>
				            <div id="collapse<?= esc(ucfirst($section['name'])); ?>" class="collapse" aria-labelledby="heading<?= esc(ucfirst($section['name'])); ?>" data-parent="#accordionBackend">
				                <div class="card-body">
				                    <div id="<?= esc($section['name']); ?>">Waiting for data...</div>
				                </div>
				            </div>
				        </div>
				    <?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>