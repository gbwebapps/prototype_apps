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
								<th style="width: 15%;">
									<a class="sort" href="#" data-column="date" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'date') ? 'asc' : 'desc'); ?>">
										Order Date&nbsp;<?= (($posts['column'] == 'date') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 15%;">
									<a class="sort" href="#" data-column="seller" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'seller') ? 'asc' : 'desc'); ?>">
										Seller&nbsp;<?= (($posts['column'] == 'seller') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 15%;">
									<a class="sort" href="#" data-column="customer" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'customer') ? 'asc' : 'desc'); ?>">
										Customer&nbsp;<?= (($posts['column'] == 'customer') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 6%;" class="text-right">
									<a class="sort" href="#" data-column="orders_net" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'orders_net') ? 'asc' : 'desc'); ?>">
										Net Total&nbsp;<?= (($posts['column'] == 'orders_net') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 6%;" class="text-right">
									<a class="sort" href="#" data-column="orders_tax" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'orders_tax') ? 'asc' : 'desc'); ?>">
										Tax Total&nbsp;<?= (($posts['column'] == 'orders_tax') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 6%;" class="text-right">
									<a class="sort" href="#" data-column="orders_total" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'orders_total') ? 'asc' : 'desc'); ?>">
										Gross Total&nbsp;<?= (($posts['column'] == 'orders_total') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 6%;" class="text-center">
									<a class="sort" href="#" data-column="products_number" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'products_number') ? 'asc' : 'desc'); ?>">
										Pcs&nbsp;<?= (($posts['column'] == 'products_number') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 6%;" class="text-center">
									<a class="sort" href="#" data-column="payment" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'payment') ? 'asc' : 'desc'); ?>">
										Payment&nbsp;<?= (($posts['column'] == 'payment') ? '&nbsp;' . $icon : ''); ?>
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

						<?php foreach($data['records']->getResult() as $order): ?>
							<?php $image = (isset($order->image) ? $order->image : null); ?>
							<tr>
								<td rowspan="2" class="align-middle text-center border-right font-weight-bold">
									<span class="badge badge-info">
										<?= esc($order->galleryOne); ?>
									</span>
								</td>
								<td rowspan="2" class="align-middle text-center border-right bg-light">
									<?php if(is_null($image)): ?>
										<span class="font-weight-bold text-danger">No picture</span>
									<?php else: ?>
										<img src="<?= base_url('images/orders/small/' . esc($image)); ?>" height="auto" width="100%" class="img-thumbnail">
									<?php endif; ?>
								</td>
								<td class="align-middle font-weight-bold">
									<a href="<?= base_url('admin/orders/show/' . esc($order->id)); ?>">
										<?= esc($order->date); ?>
									</a>
								</td>
								<td class="align-middle font-weight-bold">
									<?= esc($order->seller); ?>
								</td>
								<td class="align-middle font-weight-bold">
									<?= esc($order->customer); ?>
								</td>
								<td class="align-middle font-weight-bold text-primary text-right">
									€ <?= number_format(esc($order->orders_net), 2, ".", ""); ?>
								</td>
								<td class="align-middle font-weight-bold text-primary text-right">
									€ <?= number_format(esc($order->orders_tax), 2, ".", ""); ?>
								</td>
								<td class="align-middle font-weight-bold text-primary text-right">
									€ <?= number_format(esc($order->orders_total), 2, ".", ""); ?>
								</td>
								<td class="align-middle font-weight-bold text-center">
									<span class="badge badge-primary">
										<?= esc($order->products_number); ?>
									</span>
								</td>
								<td class="align-middle font-weight-bold text-center">
									<?= esc($order->payment); ?>
								</td>
								<td class="text-right align-middle">
									<!-- Default dropup button -->
									<div class="btn-group dropup">
										<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
											Actions
										</button>
										<div class="dropdown-menu dropdown-menu-right">
											<a href="<?= base_url('admin/orders/edit/' . esc($order->id)); ?>" class="dropdown-item">
											    <i class="fa-solid fa-edit"></i> Edit
											</a>
											<div class="dropdown-divider"></div>
										    <form method="post" class="deleteForm" data-message="Are you sure to delete the order made on <?= esc($order->date); ?>?">
										        <input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
										        <input type="hidden" name="_method" value="delete">
										        <input type="hidden" name="id" value="<?= esc($order->id); ?>">
										        <button type="submit" class="btn btn-link dropdown-item">
										            <i class="fa-solid fa-trash"></i> Delete
										        </button>
										    </form>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="9" class="align-middle">
									Created at: <span class="font-weight-bold"><?= esc($order->created_at); ?></span>
									&nbsp;&bull;&nbsp;
									Created by: <span class="font-weight-bold"><?= esc($order->created); ?></span>
									<?php if( ! is_null($order->updated_at) && ! is_null($order->updated_by)): ?>
										&nbsp;&bull;&nbsp;
										Updated at: <span class="font-weight-bold"><?= esc($order->updated_at); ?></span>
										&nbsp;&bull;&nbsp;
										Updated by: <span class="font-weight-bold"><?= esc($order->updated); ?></span>
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
