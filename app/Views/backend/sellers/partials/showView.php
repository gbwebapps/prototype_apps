<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card mt-2">

            <!-- General data -->
            <div class="card-header">
                <h2 class="card-title text-center text-md-left">General Data</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Firstname</li>
                            <li class="list-group-item font-weight-bold"><?= esc($seller->firstname); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Lastname</li>
                            <li class="list-group-item font-weight-bold"><?= esc($seller->lastname); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Email</li>
                            <li class="list-group-item font-weight-bold"><?= esc($seller->email); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Phone</li>
                            <li class="list-group-item font-weight-bold"><?= esc($seller->phone); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="row">

                    <!-- Status -->
                    <?php 
                        if($seller->status === '1'):
                            $text = 'Active';
                            $class = ' text-success font-weight-bold';
                        elseif($seller->status === '0'):
                            $text = 'Unactive';
                            $class = ' text-danger font-weight-bold';
                        endif; 
                    ?>

                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Status</li>
                            <li class="list-group-item font-weight-bold">
                                <form method="post" class="changeStatus" data-from="show" data-message="Do you want to change the status to <?= esc($seller->firstname . ' ' . $seller->lastname); ?>?">
                                    <input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                    <input type="hidden" name="id" value="<?= esc($seller->id); ?>">
                                    <button type="submit" class="btn btn-link<?= $class; ?>">
                                        <?= esc($text); ?>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- End status -->

                </div>
            </div> 
            <!-- end General Data -->

            <!-- Images -->
            <div class="card-header">
                <h2 class="card-title text-center text-md-left">Images</h2>
            </div>
            <div class="card-body">
                <div class="showGalleryOne"></div>
            </div>
            <!-- end Images -->

            <!-- Permissions -->
            <div class="card-header">
                <h2 class="card-title text-center text-md-left pt-2">Permissions</h2>
            </div>
            <div class="card-body">
                    
                <?php foreach(getPermssions() as $item): ?>

                    <div class="row permissions_box">
                        <div class="col-md-12 text-left">
                            <h5><i class="fa-solid <?= $item['icon']; ?>"></i> <?= ucfirst($item['title']); ?></h5>
                        </div>

                        <?php foreach($item['perms'] as $k => $v): ?>

                            <?php 
                                if(in_array($k, $seller_perms)):
                                    $text = 'Assigned';
                                    $class = ' text-success font-weight-bold';
                                else:
                                    $text = 'Not assigned';
                                    $class = ' text-danger font-weight-bold';
                                endif; 
                            ?>

                            <div class="col-sm-6 col-md-4 col-lg-3 text-center py-1">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><?= $v; ?></li>
                                    <li class="list-group-item">
                                        <form method="post" class="changePermission" data-from="show" data-message="Are you sure to change <?= $v; ?> permission to <?= esc($seller->firstname . ' ' . $seller->lastname); ?>?">
                                            <input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                            <input type="hidden" name="id" value="<?= esc($seller->id); ?>">
                                            <input type="hidden" name="permission" value="<?= $k; ?>">
                                            <button type="submit" class="btn btn-link<?= $class; ?>">
                                                <?= esc($text); ?>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endforeach; ?>
                    
            </div> 
            <!-- end Permissions -->

            <div class="card-header">
                <h2 class="card-title text-center text-md-left">Tokens</h2>
            </div>
            <div class="card-body p-0">
                <div id="showTokens"></div>
            </div>

            <!-- Meta tags -->
            <div class="card-header">
                <h2 class="card-title text-center text-md-left">Meta tags</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Created at</li>
                            <li class="list-group-item"><?= esc($seller->created_at); ?></li>
                        </ul>
                    </div>

                    <?php if( ! is_null($seller->updated_at)): ?>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated at</li>
                                <li class="list-group-item"><?= esc($seller->updated_at); ?></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if( ! is_null($seller->suspended_at)): ?>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Suspended at</li>
                                <li class="list-group-item"><?= esc($seller->suspended_at); ?></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if( ! is_null($seller->resetted_at)): ?>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Resetted at</li>
                                <li class="list-group-item"><?= esc($seller->resetted_at); ?></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <!-- end Meta tags -->

        </div>
    </div>
</div>
<div id="id" data-value="<?= esc($seller->id); ?>">