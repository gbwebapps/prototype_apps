<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="card mt-2">

			<form id="orders_add" method="post">

				<!-- General Data -->
				<div class="card-header">
					<div class="row">
						<div class="col-md-12 text-center text-md-left py-2 py-md-0">
							<h2 class="card-title">General Data</h2>
						</div>
					</div>
				</div>

				<div class="card-body pb-0">

					<!-- Token -->
					<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

					<div class="row">

						<!-- date order field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="date"><i class="fa-solid fa-circle-arrow-down"></i> Order Date</label>
								<div class="input-group">
								    <input type="text" class="form-control" name="date" id="orders_date" placeholder="Click here to put the order date...">
								    <div class="input-group-append">
								        <span class="input-group-text" id="basic-addon1">
								            <i class="fa-solid fa-calendar"></i>
								        </span>
								    </div>
								</div>
								<div class="error_date text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

						<!-- seller field -->
						<div class="col-md-6">

							<?php if($currentSeller->identity->master === '1'): ?>
								<div class="form-group">
									<label for="seller_id"><i class="fa-solid fa-circle-arrow-down"></i> Seller Name</label>
									<select name="seller_id" id="seller_id" class="form-control">
										<option value="">-- Select a Seller --</option>
										<?php foreach($sellers->getResult() as $seller): ?>
											<option value="<?= esc($seller->id); ?>"><?= esc($seller->identity); ?></option>
										<?php endforeach; ?>
									</select>
									<div class="error_seller_id text-danger font-weight-bold pt-1"></div>
								</div>
							<?php else: ?>
								<div class="form-group">
									<label><i class="fa-solid fa-circle-arrow-down"></i> Seller Name</label>
									<input type="text" placeholder="<?= esc($currentSeller->identity->firstname . ' ' . $currentSeller->identity->lastname); ?>" class="form-control" readonly>
									<input type="hidden" name="seller_id" value="<?= esc($currentSeller->identity->id); ?>">
								</div>
							<?php endif; ?>

						</div>

					</div>

					<div class="row">

						<!-- customer field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="customer_id"><i class="fa-solid fa-circle-arrow-down"></i> Customer Name</label>
								<select name="customer_id" id="customer_id" class="form-control">
									<option value="">-- Select a Customer --</option>
									<?php foreach($customers->getResult() as $customer): ?>
										<option value="<?= esc($customer->id); ?>"><?= esc($customer->customer); ?></option>
									<?php endforeach; ?>
								</select>
								<div class="error_customer_id text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

						<!-- payment field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="payment"><i class="fa-solid fa-circle-arrow-down"></i> Payment</label>
								<select name="payment" id="payment" class="form-control">
									<option value="">-- Select a Payment --</option>
									<option value="cash">Cash</option>
									<option value="credit">Credit</option>
								</select>
								<div class="error_payment text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-warning btn-sm" type="button" id="getProductRow">
								Add Product
							</button>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div id="productRows"></div>
						</div>
					</div>
					<div class="row">
					     <div class="col-md-12 my-3">
					          <div class="error_uniqids text-danger text-center font-weight-bold pt-1"></div>
					     </div>
					</div>

				</div>
				<!-- end General Data -->

				<!-- Images -->
				<div class="card-header">
					<h2 class="card-title">Images</h2>
				</div>
				<div class="card-body">
					<div class="row">
							
						<!-- images field -->
						<div class="col-md-12 text-center">
							<div class="form-group">
								<label for="images">Images allowed max <?= config('Displaying')->upload_file_x; ?> x <?= config('Displaying')->upload_file_y; ?> - Max <?= (config('Displaying')->upload_file_size / 1048576); ?> Mb</label>
								<input type="file" name="images[]" id="images" multiple>
								<div class="error_images text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>
				</div> 
				<!-- end Images -->
				
			</form>

		</div>
	</div>
</div>