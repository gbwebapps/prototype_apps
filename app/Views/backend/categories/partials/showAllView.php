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
								<th style="width: 75%;">
									<a class="sort" href="#" data-column="category" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'category') ? 'asc' : 'desc'); ?>">
										Category&nbsp;<?= (($posts['column'] == 'category') ? '&nbsp;' . $icon : ''); ?>
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

						<?php foreach($data['records']->getResult() as $category): ?>
							<?php $image = (isset($category->image) ? $category->image : null); ?>
							<tr>
								<td rowspan="2" class="align-middle text-center border-right font-weight-bold">
									<span class="badge badge-info">
										<?= esc($category->galleryOne); ?>
									</span>
								</td>
								<td rowspan="2" class="align-middle text-center border-right bg-light">
									<?php if(is_null($image)): ?>
										<span class="font-weight-bold text-danger">No picture</span>
									<?php else: ?>
										<img src="<?= base_url('images/categories/small/' . esc($image)); ?>" height="auto" width="100%" class="img-thumbnail">
									<?php endif; ?>
								</td>
								<td class="align-middle font-weight-bold">
									<a href="<?= base_url('admin/categories/show/' . esc($category->id)); ?>">
										<?= esc($category->category); ?>
									</a>
								</td>
								<td class="text-right align-middle">
									<!-- Default dropup button -->
									<div class="btn-group dropup">
										<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
											Actions
										</button>
										<div class="dropdown-menu dropdown-menu-right">
											<a href="<?= base_url('admin/categories/edit/' . esc($category->id)); ?>" class="dropdown-item">
											    <i class="fa-solid fa-edit"></i> Edit
											</a>
											<div class="dropdown-divider"></div>
										    <form method="post" class="deleteForm" data-message="Do you want to delete Category <?= esc($category->category); ?>?">
										        <input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
										        <input type="hidden" name="_method" value="delete">
										        <input type="hidden" name="id" value="<?= esc($category->id); ?>">
										        <button type="submit" class="btn btn-link dropdown-item">
										            <i class="fa-solid fa-trash"></i> Delete
										        </button>
										    </form>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3" class="align-middle">
									Created at: <span class="font-weight-bold"><?= esc($category->created_at); ?></span>
									&nbsp;&bull;&nbsp;
									Created by: <span class="font-weight-bold"><?= esc($category->created); ?></span>
									<?php if( ! is_null($category->updated_at) && ! is_null($category->updated_by)): ?>
										&nbsp;&bull;&nbsp;
										Updated at: <span class="font-weight-bold"><?= esc($category->updated_at); ?></span>
										&nbsp;&bull;&nbsp;
										Updated by: <span class="font-weight-bold"><?= esc($category->updated); ?></span>
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
