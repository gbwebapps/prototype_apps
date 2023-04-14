<div class="row">
	<div class="col-md-12">
		<div class="card mt-2">

			<?php if(count($data['records']->getResult())): ?>
				<div class="card-pagination">
					<?= $this->include('backend/template/paginationView'); ?>
				</div>
				<div class="card-body table-responsive p-0">

					<?php $icon = ($posts['order'] == 'desc') ? '<i class="fas fa-arrow-circle-down"></i>' : '<i class="fas fa-arrow-circle-up"></i>'; ?>
					<div id="itemlastpage" data-itemlastpage="<?= esc($data['itemLastPage']); ?>"></div>

					<table class="table text-nowrap">
						<thead>
							<tr class="sorting">
								<th style="width: 5%;" class="text-center text-primary">
									<i class="fa-solid fa-paperclip"></i>
								</th>
								<th style="width: 10%;" class="text-center text-primary">
									<i class="fa-solid fa-image"></i>
								</th>
								<th style="width: 17.5%;">
									<a class="sort" href="#" data-column="firstname" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'firstname') ? 'asc' : 'desc'); ?>">
										Firstname&nbsp;<?= (($posts['column'] == 'firstname') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 17.5%;">
									<a class="sort" href="#" data-column="lastname" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'lastname') ? 'asc' : 'desc'); ?>">
										Lastname&nbsp;<?= (($posts['column'] == 'lastname') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 20%;">
									<a class="sort" href="#" data-column="email" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'email') ? 'asc' : 'desc'); ?>">
										Email&nbsp;<?= (($posts['column'] == 'email') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 10%;">
									<a class="sort" href="#" data-column="phone" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'phone') ? 'asc' : 'desc'); ?>">
										Phone&nbsp;<?= (($posts['column'] == 'phone') ? '&nbsp;' . $icon : ''); ?>
									</a>
								</th>
								<th style="width: 10%; text-align: center;">
									<a class="sort" href="#" data-column="status" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'status') ? 'asc' : 'desc'); ?>">
										Status&nbsp;<?= (($posts['column'] == 'status') ? '&nbsp;' . $icon : ''); ?>
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

						<?php foreach($data['records']->getResult() as $seller): ?>
							<?php $image = (isset($seller->image) ? $seller->image : null); ?>
							<tr>
								<td rowspan="2" class="align-middle text-center border-right font-weight-bold">
									<span class="badge badge-info">
										<?= esc($seller->galleryOne); ?>
									</span>
								</td>
								<td rowspan="2" class="align-middle text-center border-right bg-light">
									<?php if(is_null($image)): ?>
										<span class="font-weight-bold text-danger">No picture</span>
									<?php else: ?>
										<img src="<?= base_url('images/sellers/small/' . esc($image)); ?>" height="auto" width="100%" class="img-thumbnail">
									<?php endif; ?>
								</td>
								<td class="align-middle font-weight-bold">
									<a href="<?= base_url('admin/sellers/show/' . esc($seller->id)); ?>">
										<?= esc($seller->firstname); ?>
									</a>
								</td>
								<td class="align-middle font-weight-bold">
									<a href="<?= base_url('admin/sellers/show/' . esc($seller->id)); ?>">
										<?= esc($seller->lastname); ?>
									</a>
								</td>
								<td class="align-middle font-weight-bold">
									<?= esc($seller->email); ?>
								</td>
								<td class="align-middle font-weight-bold">
									<?= esc($seller->phone); ?>
								</td>

								<!-- Status -->
								<?php 
									if($seller->status === '1'):
										$text = 'Active';
										$class = ' text-success font-weight-bold';
									elseif($seller->status === '0'):
										$text = 'Unactive';
										$class = ' text-danger font-weight-bold';
									endif; 
								?>

								<td class="align-middle text-center font-weight-bold<?= $class; ?>">
									<form method="post" class="changeStatus" data-from="showAll" data-message="Do you want to change the status to <?= esc($seller->firstname . ' ' . $seller->lastname); ?>?">
										<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
										<input type="hidden" name="id" value="<?= esc($seller->id); ?>">
										<button type="submit" class="btn btn-link<?= $class; ?>">
											<?= esc($text); ?>
										</button>
									</form>
								</td>
								<!-- End status -->

								<td class="text-right align-middle">
									<!-- Default dropup button -->
									<div class="btn-group dropup">
										<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
											Actions
										</button>
										<div class="dropdown-menu dropdown-menu-right">
											<form method="post" class="resetPsw" data-from="showAll" data-message="Do you want to reset <?= esc($seller->firstname . ' ' . $seller->lastname); ?> password?">
												<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
												<input type="hidden" name="id" value="<?= esc($seller->id); ?>">
												<button type="submit" class="btn btn-link dropdown-item">
													<i class="fa-solid fa-user-secret"></i> Reset
												</button>
											</form>
											<div class="dropdown-divider"></div>
											<a href="<?= base_url('admin/sellers/edit/' . esc($seller->id)); ?>" class="dropdown-item">
												<i class="fa-solid fa-edit"></i> Edit
											</a>
											<div class="dropdown-divider"></div>
										    <form method="post" class="deleteForm" data-message="Do you want to delete <?= esc($seller->firstname . ' ' . $seller->lastname); ?> seller?">
										    	<input type="hidden" name="_method" value="delete">
										    	<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
										    	<input type="hidden" name="id" value="<?= esc($seller->id); ?>">
										    	<button type="submit" class="btn btn-link dropdown-item">
										    		<i class="fa-solid fa-trash"></i> Delete
										    	</button>
										    </form>
										</div>
									</div>
								</td>

							</tr>

							<!-- Bottom row -->
							<tr>
								<td colspan="6" class="align-middle">

									<!-- Created part -->
									Created at: <span class="font-weight-bold"><?= esc($seller->created_at); ?></span>

									<!-- Updated part -->
									<?php if( ! is_null($seller->updated_at)): ?>
										&nbsp;&bull;&nbsp;
										Updated at: <span class="font-weight-bold"><?= esc($seller->updated_at); ?></span>
									<?php endif; ?>

									<!-- Suspended part -->
									<?php if( ! is_null($seller->suspended_at)): ?>
										&nbsp;&bull;&nbsp;
										Suspended at: <span class="font-weight-bold text-danger"><?= esc($seller->suspended_at); ?></span>
									<?php endif; ?>

									<!-- Resetted part -->
									<?php if( ! is_null($seller->resetted_at)): ?>
										&nbsp;&bull;&nbsp;
										Resetted at: <span class="font-weight-bold text-danger"><?= esc($seller->resetted_at); ?></span>
									<?php endif; ?>

								</td>
							</tr>
							<!-- End bottom row -->

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
