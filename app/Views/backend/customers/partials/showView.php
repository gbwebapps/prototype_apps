<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card mt-2">

            <!-- General data -->
            <div class="card-header">
                <h2 class="card-title">General Data</h2>
            </div>
            <div class="card-body">
                <div class="row">

                    <?php 
                        if($customer->type === '1'):
                            $text = 'Company';
                        elseif($customer->type === '0'):
                            $text = 'Private Client';
                        endif; 
                    ?>

                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Customer Type</li>
                            <li class="list-group-item"><?= esc($text); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Customer Name</li>
                            <li class="list-group-item">
                                <?= esc($customer->customer); ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Tax Code / Vat Code</li>
                            <li class="list-group-item"><?= esc($customer->tax_code); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Email</li>
                            <li class="list-group-item">
                                <?= esc($customer->email); ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Phone</li>
                            <li class="list-group-item"><?= esc($customer->phone); ?></li>
                        </ul>
                    </div>
                </div>
            </div> 
            <!-- end General Data -->

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

        </div>
    </div>
</div>
<div id="id" data-value="<?= esc($customer->id); ?>">