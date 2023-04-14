<script>
	$('[data-toggle="popover"]').popover({
		delay: {'show': 250, 'hide': 250}
	});
</script>

<div class="row">
	<div class="col-md-12">
		<div class="card mt-2">

			<?php if(count($data['records']->getResult())): ?>
				<div class="card-pagination">
					<?= $this->include('backend/template/paginationView'); ?>
				</div>
				<div class="card-body table-responsive p-0">

					<?php $icon = ($posts['order'] == 'desc') ? '<i class="fas fa-arrow-circle-down"></i>' : '<i class="fas fa-arrow-circle-up"></i>'; ?>
					<div id="itemlastpage" data-itemlastpage="<?= esc($data['itemLastPage']); ?> "></div>

					<table class="table text-nowrap">
						<thead>
							<tr class="sorting">
								<th style="width: 5%;" class="text-center text-primary">
									<i class="fa-solid fa-paperclip"></i>
								</th>
								<th style="width: 10%;" class="text-center text-primary">
									<i class="fa-solid fa-image"></i>
								</th>
								<th style="width: 10%;">
									<a class="sort" href="#" data-column="brand" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'brand') ? 'asc' : 'desc'); ?>">
										Brand&nbsp;<?= (($posts['column'] == 'brand') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 5%;" class="text-center text-primary">
									&nbsp;
								</th>
								<th style="width: 22.5%;">
									<a class="sort" href="#" data-column="product" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'product') ? 'asc' : 'desc'); ?>">
										Product&nbsp;<?= (($posts['column'] == 'product') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 7.5%;" class="text-right">
									<a class="sort" href="#" data-column="net_price" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'net_price') ? 'asc' : 'desc'); ?>">
										Net Price&nbsp;<?= (($posts['column'] == 'net_price') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 7.5%;" class="text-right">
									<a class="sort" href="#" data-column="tax_amount" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'tax_amount') ? 'asc' : 'desc'); ?>">
										Tax Amount&nbsp;<?= (($posts['column'] == 'tax_amount') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 7.5%;" class="text-right">
									<a class="sort" href="#" data-column="gross_price" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'gross_price') ? 'asc' : 'desc'); ?>">
										Gross Price&nbsp;<?= (($posts['column'] == 'gross_price') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 5%;" class="text-center">
									<a class="sort" href="#" data-column="initial_quantity" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'initial_quantity') ? 'asc' : 'desc'); ?>">
										Initial&nbsp;<?= (($posts['column'] == 'initial_quantity') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 5%;" class="text-center">
									<a class="sort" href="#" data-column="sold_quantity" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'sold_quantity') ? 'asc' : 'desc'); ?>">
										Sold&nbsp;<?= (($posts['column'] == 'sold_quantity') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 5%;" class="text-center">
									<a class="sort" href="#" data-column="available_quantity" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'available_quantity') ? 'asc' : 'desc'); ?>">
										Available&nbsp;<?= (($posts['column'] == 'available_quantity') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 10%;" class="text-right">
									<a href="#" id="linkResetSorting">
										<i class="fas fa-sync-alt"></i> 
										Reset sorting
									</a>
								</th>
							</tr>
						</thead>
						<tbody>

						<?php foreach($data['records']->getResult() as $product): ?>
							<?php $image = (isset($product->image) ? $product->image : null); ?>
							<tr>
								<td rowspan="2" class="align-middle text-center border-right font-weight-bold">
									<span class="badge badge-info">
										<?= esc($product->galleryOne); ?>
									</span>
								</td>
								<td rowspan="2" class="align-middle text-center border-right bg-light">
									<?php if(is_null($image)): ?>
										<span class="font-weight-bold text-danger">No picture</span>
									<?php else: ?>
										<img src="<?= base_url('images/products/small/' . esc($image)); ?>" height="auto" width="100%" class="img-thumbnail">
									<?php endif; ?>
								</td>
								<td class="align-middle font-weight-bold">
									<a href="<?= base_url('admin/brands/show/' . esc($product->brand_id)); ?>">
										<?= esc($product->brand); ?>
									</a>
								</td>

								<?php $cats = $productsModel->categoriesToProduct($product->id); ?>

								<td class="align-middle font-weight-bold text-center">
									<a tabindex="0" class="text-primary" role="button" data-toggle="popover" data-trigger="focus" title="Categories" data-content="<?= esc(implode(', ', $cats['categories'])); ?>">
										<i class="fa-solid fa-diagram-project" title="Click to see the categories"></i>
									</a>
								</td>
								<td class="align-middle font-weight-bold">
									<a href="<?= base_url('admin/products/show/' . esc($product->id)); ?>">
										<?= esc($product->product); ?>
									</a>
								</td>
								<td class="align-middle font-weight-bold text-primary text-right">
									€ <?= esc($product->net_price); ?>
								</td>
								<td class="align-middle font-weight-bold text-primary text-right">
									€ <?= number_format(esc($product->tax_amount), 2, ".", ""); ?>
								</td>
								<td class="align-middle font-weight-bold text-primary text-right">
									€ <?= number_format(esc($product->gross_price), 2, ".", ""); ?>
								</td>
								<td class="align-middle font-weight-bold text-center">
									<span class="badge badge-primary">
										<?= esc($product->initial_quantity); ?>
									</span>
								</td>
								<td class="align-middle font-weight-bold text-center">
									<span class="badge badge-info">
										<?= esc($product->sold_quantity); ?>
									</span>
								</td>

								<?php if(($product->available_quantity >= 1) && ($product->available_quantity < 5)):
									$badge = 'badge badge-warning';
								elseif($product->available_quantity < 1):
									$badge = 'badge badge-danger';
								else:
									$badge = 'badge badge-success';
								endif; ?>

								<td class="align-middle font-weight-bold text-center">
									<span class="<?= $badge; ?>">
										<?= esc($product->available_quantity); ?>
									</span>
								</td>
								<td class="text-right align-middle">
									<!-- Default dropup button -->
									<div class="btn-group dropup">
										<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
											Actions
										</button>
										<div class="dropdown-menu dropdown-menu-right">
											<a href="<?= base_url('admin/products/edit/' . esc($product->id)); ?>" class="dropdown-item">
											    <i class="fa-solid fa-edit"></i> Edit
											</a>
											<div class="dropdown-divider"></div>
										    <form method="post" class="deleteForm" data-message="Do you want to delete Product <?= esc($product->product); ?>?">
										        <input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
										        <input type="hidden" name="_method" value="delete">
										        <input type="hidden" name="id" value="<?= esc($product->id); ?>">
										        <button type="submit" class="btn btn-link dropdown-item">
										            <i class="fa-solid fa-trash"></i> Delete
										        </button>
										    </form>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="10" class="align-middle">
									Created at: <span class="font-weight-bold"><?= esc($product->created_at); ?></span>
									&nbsp;&bull;&nbsp;
									Created by: <span class="font-weight-bold"><?= esc($product->created); ?></span>
									<?php if( ! is_null($product->updated_at) && ! is_null($product->updated_by)): ?>
										&nbsp;&bull;&nbsp;
										Updated at: <span class="font-weight-bold"><?= esc($product->updated_at); ?></span>
										&nbsp;&bull;&nbsp;
										Updated by: <span class="font-weight-bold"><?= esc($product->updated); ?></span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>

						</tbody>
					</table>

				</div>
				<div class="card-pagination">
					<?= $this->include('backend/template/paginationView'); ?>
				</div>
			<?php else: ?>
				<div class="card-body">
					<div class="text-center">
						<span class="font-weight-bold">No records found!</span>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
