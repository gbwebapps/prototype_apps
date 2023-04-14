<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid">
		
		<!-- Token -->
		<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

		<div class="row">

			<!-- Search bar -->
			<div class="col-md-12" id="searchBar">
				<div class="row pt-3 pt-md-0">
					<div class="col-md-2">
						<div class="form-group">
							<label for="product">Search by Product</label>
							<div class="input-group">
								<input type="text" class="form-control" id="product" autocomplete="off" placeholder="Search by Product...">
							    <div class="input-group-append">
									<div class="input-group-text resetSearchFields"><i class="fa fa-times"></i></div>
							    </div>
							</div>
							<div class="error_product text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="brand_id">Search by Brand</label>
							<div class="input-group">
								<select id="brand_id" class="form-control">
									<option value="">-- Select a Brand --</option>
									<?php foreach($brandsDropdown->getResult() as $brand): ?>
										<option value="<?= esc($brand->id); ?>">
											<?= esc($brand->brand); ?>
										</option>
									<?php endforeach; ?>
								</select>
							    <div class="input-group-append">
									<div class="input-group-text resetSearchFields"><i class="fa fa-times"></i></div>
							    </div>
							</div>
							<div class="error_brand_id text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="d-none d-md-block">&nbsp;</label>
							<button type="button" class="btn btn-primary btn-block" id="linkResetSearch">
								Reset Search
							</button>
						</div>
					</div>
				</div>
			</div>
			<!-- End search bar -->

			<!-- Main data -->
			<div class="col-md-12">
				<div id="showData"></div>
			</div>
			<!-- End main Data -->

		</div>
	</div>

<?= $this->endSection(); ?>