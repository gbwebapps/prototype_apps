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
							<label for="from">Date From</label>
							<div class="input-group">
							    <input type="text" class="form-control" id="from" placeholder="From...">
							    <div class="input-group-append">
							        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
							    </div>
							</div>
							<div class="error_from text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="to">Date To</label>
							<div class="input-group">
							    <input type="text" class="form-control" id="to" placeholder="To...">
							    <div class="input-group-append">
							        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
							    </div>
							</div>
							<div class="error_to text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="seller_id">Search by Seller</label>
							<select name="seller_id" id="seller_id" class="form-control">
								<option value="">-- Search by Seller --</option>
								<?php foreach($sellers->getResult() as $seller): ?>
									<option value="<?= esc($seller->id); ?>"><?= esc($seller->identity); ?></option>
								<?php endforeach; ?>
							</select>
							<div class="error_seller_id text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="customer_id">Search by Customer</label>
							<select name="customer_id" id="customer_id" class="form-control">
								<option value="">-- Search by Customer --</option>
								<?php foreach($customers->getResult() as $customer): ?>
									<option value="<?= esc($customer->id); ?>"><?= esc($customer->customer); ?></option>
								<?php endforeach; ?>
							</select>
							<div class="error_customer_id text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="payment">Search by Payment</label>
							<select name="payment" id="payment" class="form-control">
								<option value="">-- Search by Payment --</option>
								<option value="cash">Cash</option>
								<option value="credit">Credit</option>
							</select>
							<div class="error_payment text-danger font-weight-bold pt-1"></div>
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