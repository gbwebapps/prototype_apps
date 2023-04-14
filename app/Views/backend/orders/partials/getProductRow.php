<div class="row mt-4" id="row_<?= esc($uniqid); ?>">
	<div class="col-md-12">

		<div class="jumbotron">

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="brand_id_<?= esc($uniqid); ?>"><i class="fa-solid fa-circle-arrow-down"></i> Brand</label>
						<select id="brand_id_<?= esc($uniqid); ?>" class="form-control form-control-sm brand_id brand_<?= esc($uniqid); ?>" data-uniqid="<?= esc($uniqid); ?>">
							<option value="">-- Select a Brand --</option>
							<?php foreach($brands->getResult() as $brand): ?>
								<option value="<?= esc($brand->id); ?>"><?= esc($brand->brand); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="category_id_<?= esc($uniqid); ?>"><i class="fa-solid fa-circle-arrow-down"></i> Category</label>
						<select id="category_id_<?= esc($uniqid); ?>" class="form-control form-control-sm category_id category_<?= esc($uniqid); ?>" data-uniqid="<?= esc($uniqid); ?>" disabled>
							<option value="">-- Select a Category --</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="product_id_<?= esc($uniqid); ?>"><i class="fa-solid fa-circle-arrow-down"></i> Product</label>
						<select name="product_id_<?= esc($uniqid); ?>[]" id="product_id_<?= esc($uniqid); ?>" class="form-control form-control-sm product_id product_<?= esc($uniqid); ?>" data-uniqid="<?= esc($uniqid); ?>" disabled>
							<option value="">-- Select a Product --</option>
						</select>
						<div class="error_product_id_<?= esc($uniqid); ?> text-danger font-weight-bold pt-1"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label><i class="fa-solid fa-circle-arrow-down"></i> Net Price</label>
						<input type="text" value="0.00" class="form-control form-control-sm" id="net_price_<?= esc($uniqid); ?>" readonly>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label><i class="fa-solid fa-circle-arrow-down"></i> Tax Code <span class="showTax_<?= esc($uniqid); ?>"></span></label>
						<input type="text" value="0.00" class="form-control form-control-sm" id="tax_amount_<?= esc($uniqid); ?>" readonly>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</label>
						<input type="text" value="0.00" class="form-control form-control-sm" id="gross_price_<?= esc($uniqid); ?>" readonly>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="quantity_<?= esc($uniqid); ?>"><i class="fa-solid fa-circle-arrow-down"></i> Quantity</label>
						<input type="number" min="1" id="quantity_<?= esc($uniqid); ?>" class="form-control form-control-sm quantity quantity_<?= esc($uniqid); ?>" name="quantity_<?= esc($uniqid); ?>[]" data-uniqid="<?= esc($uniqid); ?>" placeholder="Insert product quantity here..." disabled>
						<div class="error_quantity_<?= esc($uniqid); ?> text-danger font-weight-bold pt-1"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label><i class="fa-solid fa-circle-arrow-down"></i> Net Price</label>
						<input type="text" value="0.00" id="total_net_price_<?= esc($uniqid); ?>" class="form-control form-control-sm" readonly>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label><i class="fa-solid fa-circle-arrow-down"></i> Tax Code</label>
						<input type="text" value="0.00" id="total_tax_amount_<?= esc($uniqid); ?>" class="form-control form-control-sm" readonly>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</label>
						<input type="text" value="0.00" id="total_gross_price_<?= esc($uniqid); ?>" class="form-control form-control-sm" readonly>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-danger btn-sm removeProductRow" data-uniqid="<?= esc($uniqid); ?>">
						Remove Product
					</button>
				</div>
			</div>

		</div>

	</div>
	<input type="hidden" name="uniqids[]" value="<?= esc($uniqid); ?>">
</div>