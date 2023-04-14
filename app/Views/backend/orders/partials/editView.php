<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="card mt-2">

			<?php  
				$productsGetResult = [];
				$orderNetPrice = 0;
				$orderTaxAmount = 0;
				$orderGrossPrice = 0;

				foreach($orderProducts->getResult() as $product):
					$productsGetResult[] = $product;
					$taxAmount = ($product->net_price * $product->tax_percentage) / 100;
					$grossPrice = $product->net_price + $taxAmount;
					$orderNetPrice += ($product->net_price * $product->quantity);
					$orderTaxAmount += ($taxAmount * $product->quantity);
					$orderGrossPrice += ($grossPrice * $product->quantity);
				endforeach;
			?>

			<form id="orders_edit" method="post">

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
						<div class="col-md-3">
							<div class="form-group">
								<label><i class="fa-solid fa-circle-arrow-down"></i> Pcs</label>
								<input type="text" class="form-control" value="<?= esc($order->products_number); ?>" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label><i class="fa-solid fa-circle-arrow-down"></i> Net Total</label>
								<input type="text" class="form-control" value="€ <?= esc($orderNetPrice); ?>" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label><i class="fa-solid fa-circle-arrow-down"></i> Tax Total</label>
								<input type="text" class="form-control" value="€ <?= number_format(esc($orderTaxAmount), 2, ".", ""); ?>" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label><i class="fa-solid fa-circle-arrow-down"></i> Gross Total</label>
								<input type="text" class="form-control" value="€ <?= number_format(esc($orderGrossPrice), 2, ".", ""); ?>" readonly>
							</div>
						</div>
					</div>

					<div class="row">

						<!-- date order field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="date"><i class="fa-solid fa-circle-arrow-down"></i> Order Date</label>
								<div class="input-group">
								    <input type="text" class="form-control" name="date" id="orders_date" placeholder="Click here to put the order date..." value="<?= esc($order->date); ?>">
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
											<option value="<?= esc($seller->id); ?>"<?= ($order->seller_id === $seller->id) ? ' selected' : null; ?>>
												<?= esc($seller->identity); ?>
											</option>
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
										<option value="<?= esc($customer->id); ?>"<?= ($order->customer_id === $customer->id) ? ' selected' : null; ?>>
											<?= esc($customer->customer); ?>
										</option>
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
									<option value="cash"<?= ($order->payment === 'cash') ? ' selected' : null; ?>>Cash</option>
									<option value="credit"<?= ($order->payment === 'credit') ? ' selected' : null; ?>>Credit</option>
								</select>
								<div class="error_payment text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

				</div>
				<!-- end General Data -->

				<!-- Products -->
				<div class="card-header">
					<h2 class="card-title">Products</h2>
				</div>
				<div class="card-body pb-0">
					<?php foreach($productsGetResult as $product): ?>

						<div class="row mb-4" id="row_<?= esc($product->id); ?>">
							<div class="col-md-12">

								<div class="jumbotron">

									<div class="row">

										<div class="col-md-6">
											<div class="form-group">
												<label for="brand_id_<?= esc($product->id); ?>"><i class="fa-solid fa-circle-arrow-down"></i> Brand</label>
												<select id="brand_id_<?= esc($product->id); ?>" class="form-control form-control-sm brand_id brand_<?= esc($product->id); ?>" data-uniqid="<?= esc($product->id); ?>">
													<option value="">-- Select a Brand --</option>
													<?php foreach($brands->getResult() as $brand): ?>
														<option value="<?= esc($brand->id); ?>"<?= ($brand->id === $product->brand_id) ? ' selected' : null; ?>><?= esc($brand->brand); ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<?php $categories = $ordersModel->categoriesDropdown($product->brand_id); ?>

										<div class="col-md-6">
											<div class="form-group">
												<label for="category_id_<?= esc($product->id); ?>"><i class="fa-solid fa-circle-arrow-down"></i> Category</label>
												<select id="category_id_<?= esc($product->id); ?>" class="form-control form-control-sm category_id category_<?= esc($product->id); ?>" data-uniqid="<?= esc($product->id); ?>">
													<option value="">-- Select a Category --</option>
													<?php foreach($categories->getResult() as $category): ?>
														<option value="<?= esc($category->category_id); ?>"<?= ($category->category_id === $product->category_id) ? ' selected' : null; ?>><?= esc($category->category); ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

									</div>
									<div class="row">

										<?php $products = $ordersModel->productsDropdown($product->category_id, $product->brand_id); ?>

										<div class="col-md-6">
											<div class="form-group">
												<label for="product_id_<?= esc($product->id); ?>"><i class="fa-solid fa-circle-arrow-down"></i> Product</label>
												<select name="product_id_<?= esc($product->id); ?>[]" id="product_id_<?= esc($product->id); ?>" class="form-control form-control-sm product_id product_<?= esc($product->id); ?>" data-uniqid="<?= esc($product->id); ?>">
													<option value="">-- Select a Product --</option>
													<?php foreach($products->getResult() as $prod):

													    $items = $warehouse->checkStock($prod->product_id);

													    /* qui calcolo l'iva per ogni prodotto */
													    $tax_amount = ($prod->net_price * $prod->tax_percentage) / 100;

													    /* Qui sommo il prezzo base + l'iva calcolata precedentemente */
													    $gross_price = $prod->net_price + $tax_amount; 

													    /* Preparazione degli attributi a seconda di alcuni casi... */
													    if(($items['real_quantity'] >= 1) && ($items['real_quantity'] < 5)):
													        /* La giacenza è maggiore di 1 ma minore di 5, warning! */
													        $attr = 'class="font-weight-bold text-warning" 
													                 net_price="' . esc($prod->net_price) . '" 
													                 tax_amount = "' . esc($tax_amount) . '" 
													                 gross_price = "' . esc($gross_price) . '" 
													                 tax_percentage = "' . esc($prod->tax_percentage) . '"';
													    elseif($items['real_quantity'] < 1):
													        /* La giacenza è minore di 1, danger! */
													        $attr = 'class="font-weight-bold text-danger" 
													                 net_price="' . esc($prod->net_price) . '" 
													                 tax_amount = "' . esc($tax_amount) . '" 
													                 gross_price = "' . esc($gross_price) . '" 
													                 tax_percentage = "' . esc($prod->tax_percentage) . '" disabled';
													    else:
													        /* La giacenza è più di 5, siamo tranquilli */
													        $attr = 'class="font-weight-bold text-success" 
													                 net_price="' . esc($prod->net_price) . '" 
													                 tax_amount = "' . esc($tax_amount) . '" 
													                 gross_price = "' . esc($gross_price) . '" 
													                 tax_percentage = "' . esc($prod->tax_percentage) . '"';
													    endif; ?>

													    <option value="<?= esc($prod->product_id); ?>"<?= $attr; ?><?= ($product->id === $prod->product_id) ? ' selected' : null; ?>>
													        <?= esc($prod->product . ' - (' . $items['real_quantity'] . ')'); ?>
													    </option>';

													<?php endforeach; ?>
												</select>
												<div class="error_product_id_<?= esc($product->id); ?> text-danger font-weight-bold pt-1"></div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label><i class="fa-solid fa-circle-arrow-down"></i> Net Price</label>
												<input type="text" value="<?= esc($product->net_price); ?>" class="form-control form-control-sm" id="net_price_<?= esc($product->id); ?>" readonly>
											</div>
										</div>

										<?php
											$taxAmount = ($product->net_price * $product->tax_percentage) / 100;
											$grossPrice = $product->net_price + $taxAmount;
										?>

										<div class="col-md-2">
											<div class="form-group">
												<label><i class="fa-solid fa-circle-arrow-down"></i> Tax Code <span class="showTax_<?= esc($product->id); ?>"></span></label>
												<input type="text" value="<?= number_format(esc($taxAmount), 2, ".", ""); ?>" class="form-control form-control-sm" id="tax_amount_<?= esc($product->id); ?>" readonly>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</label>
												<input type="text" value="<?= number_format(esc($grossPrice), 2, ".", ""); ?>" class="form-control form-control-sm" id="gross_price_<?= esc($product->id); ?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">

										<?php 
											$totalNetPrice = $product->net_price * $product->quantity;
											$totalTaxAmount = $taxAmount * $product->quantity;
											$totalGrossPrice = $grossPrice * $product->quantity;
										?>

										<div class="col-md-6">
											<div class="form-group">
												<label for="quantity_<?= esc($product->id); ?>">Quantity</label>
												<input type="number" min="1" id="quantity_<?= esc($product->id); ?>" class="form-control form-control-sm quantity quantity_<?= esc($product->id); ?>" name="quantity_<?= esc($product->id); ?>[]" value="<?= esc($product->quantity); ?>" data-uniqid="<?= esc($product->id); ?>" placeholder="Insert product quantity here...">
												<div class="error_quantity_<?= esc($product->id); ?> text-danger font-weight-bold pt-1"></div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label><i class="fa-solid fa-circle-arrow-down"></i> Net Price</label>
												<input type="text" value="<?= esc($totalNetPrice); ?>" id="total_net_price_<?= esc($product->id); ?>" class="form-control form-control-sm" readonly>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label><i class="fa-solid fa-circle-arrow-down"></i> Tax Code</label>
												<input type="text" value="<?= number_format(esc($totalTaxAmount), 2, ".", ""); ?>" id="total_tax_amount_<?= esc($product->id); ?>" class="form-control form-control-sm" readonly>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</label>
												<input type="text" value="<?= number_format(esc($totalGrossPrice), 2, ".", ""); ?>" id="total_gross_price_<?= esc($product->id); ?>" class="form-control form-control-sm" readonly>
											</div>
										</div>
										
									</div>
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="button" class="btn btn-danger btn-sm removeProductRow" data-uniqid="<?= esc($product->id); ?>">
												Remove Product
											</button>
										</div>
									</div>

								</div>

							</div>
							<input type="hidden" name="uniqids[]" value="<?= esc($product->id); ?>">
						</div>

					<?php endforeach; ?>

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
				<!-- end Products -->

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
				                <li class="list-group-item"><?= esc($order->created_at); ?></li>
				            </ul>
				        </div>
				        <div class="col-md-6">
				            <ul class="list-group list-group-flush">
				                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Created by</li>
				                <li class="list-group-item"><?= esc($order->created); ?></li>
				            </ul>
				        </div>
				    </div>

				    <?php if( ! is_null($order->updated_at) && ! is_null($order->updated_by)): ?>
					    <div class="row">
					        <div class="col-md-6">
					            <ul class="list-group list-group-flush">
					                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated at</li>
					                <li class="list-group-item"><?= esc($order->updated_at); ?></li>
					            </ul>
					        </div>
					        <div class="col-md-6">
					            <ul class="list-group list-group-flush">
					                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated by</li>
					                <li class="list-group-item"><?= esc($order->updated); ?></li>
					            </ul>
					        </div>
					    </div>
					<?php endif; ?>

				</div>
				<!-- end Meta tags -->
				
				<input type="hidden" name="id" value="<?= esc($order->id); ?>">
			</form>

		</div>
	</div>
</div>