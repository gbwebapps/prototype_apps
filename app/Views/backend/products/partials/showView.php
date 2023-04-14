<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card mt-2">

            <!-- General data -->
            <div class="card-header">
                <h2 class="card-title">General Data</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Product</li>
                            <li class="list-group-item font-weight-bold"><?= esc($product->product); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Description</li>
                            <li class="list-group-item font-weight-bold">
                                <?= esc($product->description); ?>
                            </li>
                        </ul>
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
            		<div class="col-md-6">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Brand</li>
            		        <li class="list-group-item font-weight-bold"><?= esc($product->brand); ?></li>
            		    </ul>
            		</div>
            	</div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Categories</li>
                            <li class="list-group-item font-weight-bold"><?= esc(implode(', ', $cats['categories'])); ?></li>
                        </ul>
                    </div>
                </div>
            	<div class="row">
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Unit</li>
            		        <li class="list-group-item font-weight-bold"><?= esc($product->unit); ?></li>
            		    </ul>
            		</div>
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Quantity</li>
            		        <li class="list-group-item font-weight-bold"><?= esc($product->initial_quantity); ?></li>
            		    </ul>
            		</div>
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Sold</li>
            		        <li class="list-group-item font-weight-bold"><?= esc($product->sold_quantity); ?></li>
            		    </ul>
            		</div>
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Available</li>
            		        <li class="list-group-item font-weight-bold"><?= esc($product->available_quantity); ?></li>
            		    </ul>
            		</div>
            	</div>
            	<div class="row">
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Tax Percentage</li>
            		        <li class="list-group-item font-weight-bold"><?= esc($product->tax_percentage); ?></li>
            		    </ul>
            		</div>
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Net Price</li>
            		        <li class="list-group-item font-weight-bold">€ <?= esc($product->net_price); ?></li>
            		    </ul>
            		</div>
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Tax Amount</li>
            		        <li class="list-group-item font-weight-bold">€ <?= number_format(esc($product->tax_amount), 2, ".", ""); ?></li>
            		    </ul>
            		</div>
            		<div class="col-md-3">
            		    <ul class="list-group list-group-flush">
            		        <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</li>
            		        <li class="list-group-item font-weight-bold">€ <?= number_format(esc($product->gross_price), 2, ".", ""); ?></li>
            		    </ul>
            		</div>
            	</div>
            </div>

            <!-- Images -->
            <div class="card-header">
                <h2 class="card-title">Images</h2>
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

        </div>
    </div>
</div>
<div id="id" data-value="<?= esc($product->id); ?>">