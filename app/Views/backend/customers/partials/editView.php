<div class="row">
	<div class="col-md-6 offset-md-3">
		<div class="card mt-2">

			<form id="customers_edit" method="post">

				<!-- General Data -->
				<div class="card-header">
					<div class="row">
						<div class="col-md-12 text-center text-md-left py-2 py-md-0">
							<h2 class="card-title">General Data</h2>
						</div>
					</div>
				</div>

				<div class="card-body">

					<!-- Token -->
					<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

					<div class="row">

						<!-- type field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="type"><i class="fa-solid fa-circle-arrow-down"></i> Customer Type</label>
								<select name="type" id="type" class="form-control">
									<option value="">-- Select a Type --</option>
									<option value="0"<?= ($customer->type === '0') ? ' selected' : null; ?>>Private Client</option>
									<option value="1"<?= ($customer->type === '1') ? ' selected' : null; ?>>Company</option>
								</select>
								<div class="error_type text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

						<!-- customer field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="customer"><i class="fa-solid fa-circle-arrow-down"></i> Customer Name</label>
								<input type="text" class="form-control" name="customer" id="customer" placeholder="Insert the customer name here..." value="<?= esc($customer->customer); ?>">
								<div class="error_customer text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">

						<!-- tax_code field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="tax_code"><i class="fa-solid fa-circle-arrow-down"></i> Tax Code / Vat Code</label>
								<input type="text" class="form-control" name="tax_code" id="tax_code" placeholder="Insert the tax code here..." value="<?= esc($customer->tax_code); ?>">
								<div class="error_tax_code text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

						<!-- email field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="email"><i class="fa-solid fa-circle-arrow-down"></i> Email</label>
								<input type="text" class="form-control" name="email" id="email" placeholder="Insert the email here..."value="<?= esc($customer->email); ?>">
								<div class="error_email text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">

						<!-- phone field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone"><i class="fa-solid fa-circle-arrow-down"></i> Phone</label>
								<input type="text" class="form-control" name="phone" id="phone" placeholder="Insert the phone here..."value="<?= esc($customer->phone); ?>">
								<div class="error_phone text-danger font-weight-bold pt-1"></div>
							</div>
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
				<div class="card-body">
				    <div class="showGalleryOne"></div>
				</div>
				<!-- end Images -->

				<!-- Meta tags -->
				<div class="card-header">
				    <h2 class="card-title">Meta tags</h2>
				</div>
				<div class="card-body">
				    <div class="row">
				        <div class="col-md-6">
				            <ul class="list-group list-group-flush">
				                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Created at</li>
				                <li class="list-group-item"><?= esc($customer->created_at); ?></li>
				            </ul>
				        </div>
				        <div class="col-md-6">
				            <ul class="list-group list-group-flush">
				                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Created by</li>
				                <li class="list-group-item"><?= esc($customer->created); ?></li>
				            </ul>
				        </div>
				    </div>

				    <?php if( ! is_null($customer->updated_at) && ! is_null($customer->updated_by)): ?>
					    <div class="row">
					        <div class="col-md-6">
					            <ul class="list-group list-group-flush">
					                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated at</li>
					                <li class="list-group-item"><?= esc($customer->updated_at); ?></li>
					            </ul>
					        </div>
					        <div class="col-md-6">
					            <ul class="list-group list-group-flush">
					                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated by</li>
					                <li class="list-group-item"><?= esc($customer->updated); ?></li>
					            </ul>
					        </div>
					    </div>
					<?php endif; ?>

				</div>
				<!-- end Meta tags -->
				
				<input type="hidden" name="id" value="<?= esc($customer->id); ?>">
			</form>

		</div>
	</div>
</div>