<div class="row">

	<?php if(count($images)): ?>

		<?php foreach($images as $image): ?>

			<div class="col-sm-6 col-md-4 mb-3">

				<?php if($view === 'show'): ?>

					<a href="<?= base_url('images/' . $entity . '/large/' . esc($image->url)); ?>" target="_blank">
						<img src="<?= base_url('images/' . $entity . '/medium/' . esc($image->url)); ?>" height="auto" width="100%" class="img-thumbnail">
					</a>
					
				<?php elseif($view === 'edit'): ?>

					<div class="container-image">
						<img src="<?= base_url('images/' . $entity . '/medium/' . esc($image->url)); ?>" height="auto" width="100%" class="img-thumbnail overGalleryOne">
						<?php if($image->is_cover === '1'): ?>
							<div class="checked">
								<i class="fas fa-check-circle fa-2x"></i>
							</div>
							<?php $class = 'removeCoverGalleryOne'; ?>
							<?php $subClass = ' remove'; ?>
							<?php $text = 'Remove Cover'; ?>
						<?php else: ?>
							<?php $class = 'setCoverGalleryOne'; ?>
							<?php $subClass = ' cover'; ?>
							<?php $text = 'Set Cover'; ?>
						<?php endif; ?>
						<div class="middle">
							<a href="#" class="<?= $class; ?>" data-id="<?= esc($image->id); ?>" data-entityid="<?= esc($id); ?>">
								<div class="text<?= $subClass; ?>"><?= $text; ?></div>
							</a>
						</div>
					</div>
					<div class="text-center mt-1">
						<a href="#" class="deleteGalleryOne" data-id="<?= esc($image->id); ?>" data-entityid="<?= esc($id); ?>">
							<i class="fas fa-times"></i>&nbsp;Delete
						</a>
					</div>

				<?php endif; ?>

			</div>

		<?php endforeach; ?>

	<?php else: ?>

		<div class="col-md-12 text-center">
			<span class="font-weight-bold">No images found!</span>
		</div>

	<?php endif; ?>

</div>