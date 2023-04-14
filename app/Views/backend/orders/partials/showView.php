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

            <!-- General data -->
            <div class="card-header">
                <h2 class="card-title">General Data</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Pcs</li>
                            <li class="list-group-item"><?= esc($order->products_number); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Net Total</li>
                            <li class="list-group-item">€ <?= esc($orderNetPrice); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Tax Total</li>
                            <li class="list-group-item">€ <?= number_format(esc($orderTaxAmount), 2, ".", ""); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Gross Total</li>
                            <li class="list-group-item">€ <?= number_format(esc($orderGrossPrice), 2, ".", ""); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Order Date</li>
                            <li class="list-group-item"><?= esc($order->date); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Seller Name</li>
                            <li class="list-group-item">
                                <?= esc($order->seller); ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Customer Name</li>
                            <li class="list-group-item"><?= esc($order->customer); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Payment</li>
                            <li class="list-group-item">
                                <?= esc($order->payment); ?>
                            </li>
                        </ul>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="jumbotron mb-3">
                                
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Brand</li>
                                            <li class="list-group-item"><?= esc($product->brand); ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Category</li>
                                            <li class="list-group-item">
                                                <?= esc($product->category); ?>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Product</li>
                                            <li class="list-group-item">
                                                <?= esc($product->product); ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Net Price</li>
                                            <li class="list-group-item">
                                                <?= esc($product->net_price); ?>
                                            </li>
                                        </ul>
                                    </div>

                                    <?php
                                        $taxAmount = ($product->net_price * $product->tax_percentage) / 100;
                                        $grossPrice = $product->net_price + $taxAmount;
                                    ?>

                                    <div class="col-md-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Tax Code</li>
                                            <li class="list-group-item">
                                                <?= number_format(esc($taxAmount), 2, ".", ""); ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</li>
                                            <li class="list-group-item">
                                                <?= number_format(esc($grossPrice), 2, ".", ""); ?>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <div class="row">

                                    <?php 
                                        $totalNetPrice = $product->net_price * $product->quantity;
                                        $totalTaxAmount = $taxAmount * $product->quantity;
                                        $totalGrossPrice = $grossPrice * $product->quantity;
                                    ?>
                                    
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Quantity</li>
                                            <li class="list-group-item">
                                                <?= esc($product->quantity); ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Net Price</li>
                                            <li class="list-group-item">
                                                <?= esc($totalNetPrice); ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Tax Code</li>
                                            <li class="list-group-item">
                                                <?= number_format(esc($totalTaxAmount), 2, ".", ""); ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Gross Price</li>
                                            <li class="list-group-item">
                                                <?= number_format(esc($totalGrossPrice), 2, ".", ""); ?>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
            <!-- end Products -->

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

        </div>
    </div>
</div>
<div id="id" data-value="<?= esc($order->id); ?>">