<?php if($type === 'net_tax_final'): ?>

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="tax_percentage"><i class="fa-solid fa-circle-arrow-down"></i> Tax Percentage</label>
				<select name="tax_percentage" id="tax_percentage" class="form-control">
					<option value="">-- Select a Tax Percentage --</option>
				
					<?php if(is_null($id)): ?>
						<option value="4">4%</option>
						<option value="10">10%</option>
						<option value="22">22%</option>
					<?php else: ?>
						<option value="4"<?= ($product->tax_percentage == 4 ? ' selected' : ''); ?>>4%</option>
						<option value="10"<?= ($product->tax_percentage == 10 ? ' selected' : ''); ?>>10%</option>
						<option value="22"<?= ($product->tax_percentage == 22 ? ' selected' : ''); ?>>22%</option>
					<?php endif; ?>

				</select>
				<div class="error_tax_percentage text-danger font-weight-bold pt-1"></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="net_price"><i class="fa-solid fa-circle-arrow-down"></i> Net Price</label>

				<?php if(is_null($id)): ?>
					<input type="text" name="net_price" id="net_price" class="form-control" placeholder="0.00">
				<?php else: ?>
					<input type="text" name="net_price" id="net_price" class="form-control" placeholder="0.00" value="<?= esc($product->net_price); ?>">
				<?php endif; ?>

				<div class="error_net_price text-danger font-weight-bold pt-1"></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="tax_amount"><i class="fa-solid fa-circle-arrow-down"></i> Tax Amount <span class="showTax"></span></label>
				
				<?php if(is_null($id)): ?>
					<input type="text" class="form-control" value="0.00" id="tax_amount" readonly>
				<?php else: ?>
					<input type="text" class="form-control" value="<?= number_format(esc($product->tax_amount), 2, ".", ""); ?>" id="tax_amount" readonly>
				<?php endif; ?>

			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="gross_price"><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</label>
				
				<?php if(is_null($id)): ?>
					<input type="text" class="form-control" value="0.00" id="gross_price" readonly>
				<?php else: ?>
					<input type="text" class="form-control" value="<?= number_format(esc($product->gross_price), 2, ".", ""); ?>" id="gross_price" readonly>
				<?php endif; ?>

			</div>
		</div>

		<script>
			$(document).on("keyup", "#net_price", function(e){
				var net_price = $(this).val();
				var tax_percentage = $("#tax_percentage").val();
				var tax_amount = (Number(net_price) * Number(tax_percentage)) / Number(100);
				var gross_price = Number(net_price) + Number(tax_amount);
				$("#tax_amount").val(tax_amount.toFixed(2));
				$("#gross_price").val(gross_price.toFixed(2));
			});

			$(document).on("change", "#tax_percentage", function(e){
				var tax_percentage = $(this).val();
				var net_price = $("#net_price").val();
				var tax_amount = (Number(net_price) * Number(tax_percentage)) / Number(100);
				var gross_price = Number(net_price) + Number(tax_amount);
				(tax_percentage == undefined || tax_percentage == "") ? $(".showTax").text("") : $(".showTax").text("(" + tax_percentage + "%)");
				$("#net_price").val(Number(net_price).toFixed(2));
				$("#tax_amount").val(tax_amount.toFixed(2));
				$("#gross_price").val(gross_price.toFixed(2));
			});
		</script>

		<div class="col-md-12 text-center">
			<div class="form-group">
				<button type="button" class="btn btn-sm btn-info" id="reverseBtn" data-type="final_tax_net"<?= (( ! is_null($id)) ? ' data-id="' . esc($id) . '"' : null); ?>>
					Reverse
				</button>
			</div>
		</div>
	</div>

<?php elseif($type === 'final_tax_net'): ?>

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="tax_percentage"><i class="fa-solid fa-circle-arrow-down"></i> Tax Percentage</label>
				<select name="tax_percentage" id="tax_percentage" class="form-control">
					<option value="">-- Select a Tax Percentage --</option>
					
					<?php if(is_null($id)): ?>
						<option value="4">4%</option>
						<option value="10">10%</option>
						<option value="22">22%</option>
					<?php else: ?>
						<option value="4"<?= ($product->tax_percentage == 4 ? ' selected' : ''); ?>>4%</option>
						<option value="10"<?= ($product->tax_percentage == 10 ? ' selected' : ''); ?>>10%</option>
						<option value="22"<?= ($product->tax_percentage == 22 ? ' selected' : ''); ?>>22%</option>
					<?php endif; ?>

				</select>
				<div class="error_tax_percentage text-danger font-weight-bold pt-1"></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="gross_price"><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</label>
					
				<?php if(is_null($id)): ?>
					<input type="text" name="gross_price" class="form-control" value="0.00" id="gross_price">
				<?php else: ?>
					<input type="text" name="gross_price" class="form-control" value="<?= number_format(esc($product->gross_price), 2, ".", ""); ?>" id="gross_price">
				<?php endif; ?>

				<div class="error_gross_price text-danger font-weight-bold pt-1"></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="tax_amount"><i class="fa-solid fa-circle-arrow-down"></i> Tax Amount <span class="showTax"></span></label>
				
				<?php if(is_null($id)): ?>
					<input type="text" class="form-control" value="0.00" id="tax_amount" readonly>
				<?php else: ?>
					<input type="text" class="form-control" value="<?= number_format(esc($product->tax_amount), 2, ".", ""); ?>" id="tax_amount" readonly>
				<?php endif; ?>

			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="net_price"><i class="fa-solid fa-circle-arrow-down"></i> Net Price</label>

				<?php if(is_null($id)): ?>
					<input type="text" id="net_price" name="net_price" class="form-control" value="0.00" readonly>
				<?php else: ?>
					<input type="text" id="net_price" name="net_price" class="form-control" value="<?= esc($product->net_price); ?>" readonly>
				<?php endif; ?>

			</div>
		</div>

		<script>
			$(document).on("keyup", "#gross_price", function(e){
				var gross_price = $(this).val();
				var tax_percentage = $("#tax_percentage").val();
				var net_price = ((Number(100) * Number(gross_price)) / (Number(100) + Number(tax_percentage)));
				var tax_amount = Number(gross_price) - Number(net_price);
				$("#tax_amount").val(tax_amount.toFixed(2));
				(net_price == undefined || net_price == "") ? $("#net_price").val("") : $("#net_price").val(net_price.toFixed(2));
			});

			$(document).on("change", "#tax_percentage", function(e){
				var tax_percentage = $(this).val();
				var gross_price = $("#gross_price").val();
				var net_price = ((Number(100) * Number(gross_price)) / (Number(100) + Number(tax_percentage)));
				(tax_percentage == undefined || tax_percentage == "") ? $(".showTax").text("") : $(".showTax").text("(" + tax_percentage + "%)");
				var tax_amount = Number(gross_price) - Number(net_price);
				$("#tax_amount").val(tax_amount.toFixed(2));
				(net_price == undefined || net_price == "") ? $("#net_price").val("") : $("#net_price").val(net_price.toFixed(2));
			});
		</script>

		<div class="col-md-12 text-center">
			<div class="form-group">
				<button type="button" class="btn btn-sm btn-info" id="reverseBtn" data-type="net_tax_final"<?= (( ! is_null($id)) ? ' data-id="' . esc($id) . '"' : null); ?>>
					Reverse
				</button>
			</div>
		</div>
	</div>

<?php endif; ?>