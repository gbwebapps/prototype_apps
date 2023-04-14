<!-- colClass - to put some classes in the main col -->
<!-- colAttr - to put some attributes in the main col -->
<!-- barItemClass - to put some classes in the bar_item div -->
<!-- barItemAttr - to put some attributes in the bar_item div -->
<!-- linkClass - to put some classes in the link tag -->
<!-- linkAttr - to put some attributes in the link tag -->

<?php if(isset($linksBar)): ?>
	<div class="container-fluid mt-3">
		<div class="row">
			<div class="col-md-12">
				<div class="bar">
					<div class="row">
						<?php foreach($linksBar as $link): ?>
							<div class="col-md-3<?= (isset($link['colClass'])) ? $link['colClass'] : null; ?>"<?= (isset($link['colAttr'])) ? $link['colAttr'] : null; ?>>
								<?php if( ! empty($link)): ?>
								<div class="bar_item<?= (isset($link['barItemClass'])) ? $link['barItemClass'] : null; ?>"<?= (isset($link['barItemAttr'])) ? $link['barItemAttr'] : null; ?>>
									<a <?= (isset($link['linkClass'])) ? 'class="' . $link['linkClass'] . '"' : null; ?> href="<?= base_url($link['route']); ?>"<?= (isset($link['linkAttr'])) ? $link['linkAttr'] : null; ?>>
										<i class="<?= $link['icon']; ?>"></i> <?= $link['label']; ?>
									</a>
								</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>