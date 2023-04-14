<div class="row">
	<div class="col-md-6 offset-md-3">
		<div class="card mt-2">

			<form id="products_add" method="post">

				<!-- Token -->
				<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

				<!-- General Data -->
				<div class="card-header">
					<div class="row">
						<div class="col-md-12 text-center text-md-left py-2 py-md-0">
							<h2 class="card-title">General Data</h2>
						</div>
					</div>
				</div>

				<div class="card-body">

					<div class="row">
						
						<!-- product field -->
						<div class="col-md-12">
							<div class="form-group">
								<label for="product"><i class="fa-solid fa-circle-arrow-down"></i> Product</label>
								<input type="text" name="product" id="product" placeholder="Insert the product here..." class="form-control">
								<div class="error_product text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">

						<!-- description field -->
						<div class="col-md-12">
							<div class="form-group">
								<label for="description"><i class="fa-solid fa-circle-arrow-down"></i> Description</label>
								<textarea name="description" id="description" rows="7" placeholder="Insert the description here..." class="form-control"></textarea>
								<div class="error_description text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

				</div>
				<!-- end General Data -->

				<!-- Product Data -->
				<div class="card-header">
					<div class="row">
						<div class="col-md-12 text-center text-md-left py-2 py-md-0">
							<h2 class="card-title">Product Data</h2>
						</div>
					</div>
				</div>
				<div class="card-body">

					<div class="row">

						<!-- brand_id field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="select_brand_id"><i class="fa-solid fa-circle-arrow-down"></i> Brand</label>
								<select name="brand_id" id="select_brand_id" class="form-control">
									<option value="">-- Select a Brand --</option>
									<?php foreach($brandsDropdown->getResult() as $brand): ?>
										<option value="<?= esc($brand->id); ?>"><?= esc($brand->brand); ?></option>
									<?php endforeach; ?>
								</select>
								<div class="error_brand_id text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>
					<div class="row">

						<!-- categories field -->
						<div class="col-md-12">
							<div class="form-group">
								<label for="categoriesProducts"><i class="fa-solid fa-circle-arrow-down"></i> Categories</label>
								<select name="categoriesProducts[]" id="categoriesProducts" size="10" class="form-control" multiple disabled>
								</select>
								<div class="error_categoriesProducts text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="unit"><i class="fa-solid fa-circle-arrow-down"></i> Unit</label>
								<select name="unit" id="unit" class="form-control">
									<option value="">-- Select a Unit --</option>
									<option value="Bags">Bags</option>
									<option value="Bottles">Bottles</option>
									<option value="Box">Box</option>
									<option value="Dozens">Dozens</option>
									<option value="Feet">Feet</option>
									<option value="Gallon">Gallon</option>
									<option value="Grams">Grams</option>
									<option value="Inch">Inch</option>
									<option value="Kg">Kg</option>
									<option value="Liters">Liters</option>
									<option value="Meter">Meter</option>
									<option value="Nos">Nos</option>
									<option value="Packet">Packet</option>
									<option value="Rolls">Rolls</option>
								</select>
								<div class="error_unit text-danger font-weight-bold pt-1"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="initial_quantity"><i class="fa-solid fa-circle-arrow-down"></i> Quantity</label>
								<input type="text" name="initial_quantity" id="initial_quantity" class="form-control" placeholder="Initial Quantity...">
								<div class="error_initial_quantity text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div id="priceCalculateZone"></div>

				</div>
				<!-- end Product Data -->

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