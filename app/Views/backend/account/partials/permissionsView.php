<div class="card">
    <div class="card-header">
        <h2 class="card-title text-center text-md-left">Permissions</h2>
    </div>
    
	<div class="card-body">
	                
	    <!-- Permissions -->    
        <?php foreach(getPermssions() as $item): ?>

            <div class="row permissions_box">
                <div class="col-md-12 text-left">
                    <h5><i class="fa-solid <?= $item['icon']; ?>"></i> <?= ucfirst($item['title']); ?></h5>
                </div>

                <?php foreach($item['perms'] as $k => $v): ?>

                    <?php 
                        if((isset($currentSeller->permissions)) && (in_array($k, $currentSeller->permissions))):
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
                            <li class="list-group-item<?= $class; ?>">
                            	<?= esc($text); ?>
                            </li>
                        </ul>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endforeach; ?>
        <!-- end Permissions -->
            
    </div> 
</div>