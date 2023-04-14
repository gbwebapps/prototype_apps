<div class="row">
	<div class="col-md-6 offset-md-3">
		<div class="card mt-2">

			<form id="products_edit" method="post">

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
								<input type="text" name="product" id="product" placeholder="Insert the product here..." class="form-control" value="<?= esc($product->product); ?>">
								<div class="error_product text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">

						<!-- description field -->
						<div class="col-md-12">
							<div class="form-group">
								<label for="description"><i class="fa-solid fa-circle-arrow-down"></i> Description</label>
								<textarea name="description" id="description" rows="7" placeholder="Insert the description here..." class="form-control"><?= esc($product->description); ?></textarea>
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
										<option value="<?= esc($brand->id); ?>"<?= ($brand->id === $product->brand_id) ? ' selected' : null; ?>>
											<?= esc($brand->brand); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<div class="error_brand_id text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>
					<div class="row">

						<!-- categoriesProducts field -->
						<div class="col-md-12">
							<div class="form-group">
								<label for="categoriesProducts"><i class="fa-solid fa-circle-arrow-down"></i> Categories</label>
								<select name="categoriesProducts[]" id="categoriesProducts" size="10" class="form-control" multiple>
									<?php foreach($categoriesDropdown->getResult() as $categoryDropdown): ?>
										<?= $selected = (in_array($categoryDropdown->category_id, $cats['ids']) ? ' selected' : ''); ?>
										<option value="<?= esc($categoryDropdown->category_id); ?>"<?= $selected; ?>>
											<?= esc($categoryDropdown->category); ?>
										</option>
									<?php endforeach; ?>
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
									<option value="Bags"<?= ($product->unit === 'Bags') ? ' selected' : null; ?>>Bags</option>
									<option value="Bottles"<?= ($product->unit === 'Bottles') ? ' selected' : null; ?>>Bottles</option>
									<option value="Box"<?= ($product->unit === 'Box') ? ' selected' : null; ?>>Box</option>
									<option value="Dozens"<?= ($product->unit === 'Dozens') ? ' selected' : null; ?>>Dozens</option>
									<option value="Feet"<?= ($product->unit === 'Feet') ? ' selected' : null; ?>>Feet</option>
									<option value="Gallon"<?= ($product->unit === 'Gallon') ? ' selected' : null; ?>>Gallon</option>
									<option value="Grams"<?= ($product->unit === 'Grams') ? ' selected' : null; ?>>Grams</option>
									<option value="Inch"<?= ($product->unit === 'Inch') ? ' selected' : null; ?>>Inch</option>
									<option value="Kg"<?= ($product->unit === 'Kg') ? ' selected' : null; ?>>Kg</option>
									<option value="Liters"<?= ($product->unit === 'Liters') ? ' selected' : null; ?>>Liters</option>
									<option value="Meter"<?= ($product->unit === 'Meter') ? ' selected' : null; ?>>Meter</option>
									<option value="Nos"<?= ($product->unit === 'Nos') ? ' selected' : null; ?>>Nos</option>
									<option value="Packet"<?= ($product->unit === 'Packet') ? ' selected' : null; ?>>Packet</option>
									<option value="Rolls"<?= ($product->unit === 'Rolls') ? ' selected' : null; ?>>Rolls</option>
								</select>
								<div class="error_unit text-danger font-weight-bold pt-1"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="initial_quantity"><i class="fa-solid fa-circle-arrow-down"></i> Quantity</label>
								<input type="text" name="initial_quantity" id="initial_quantity" class="form-control" placeholder="Initial Quantity..." value="<?= esc($product->initial_quantity); ?>">
								<div class="error_initial_quantity text-danger font-weight-bold pt-1"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label><i class="fa-solid fa-circle-arrow-down"></i> Sold</label>
								<input type="text" placeholder="<?= esc($product->sold_quantity); ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label><i class="fa-solid fa-circle-arrow-down"></i> Available</label>
								<input type="text" id="available" value="<?= esc($product->available_quantity); ?>" class="form-control" readonly>
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
				                <li class="list-group-item"><?= esc($product->created_at); ?></li>
				            </ul>
				        </div>
				        <div class="col-md-6">
				            <ul class="list-group list-group-flush">
				                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Created by</li>
				                <li class="list-group-item"><?= esc($product->created); ?></li>
				            </ul>
				        </div>
				    </div>

				    <?php if( ! is_null($product->updated_at) && ! is_null($product->updated_by)): ?>
					    <div class="row">
					        <div class="col-md-6">
					            <ul class="list-group list-group-flush">
					                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated at</li>
					                <li class="list-group-item"><?= esc($product->updated_at); ?></li>
					            </ul>
					        </div>
					        <div class="col-md-6">
					            <ul class="list-group list-group-flush">
					                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated by</li>
					                <li class="list-group-item"><?= esc($product->updated); ?></li>
					            </ul>
					        </div>
					    </div>
					<?php endif; ?>

				</div>
				<!-- end Meta tags -->
				
				<input type="hidden" name="id" value="<?= esc($product->id); ?>">
			</form>

		</div>
	</div>
</div>