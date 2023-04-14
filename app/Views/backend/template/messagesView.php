<?php if(session('danger')): ?>
    <div class="container-fluid">  
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger mb-0 mt-3">
                    <?= session('danger'); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(session('success')): ?>
    <div class="container-fluid">  
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="alert alert-success mb-0 mt-3">
                    <?= session('success'); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>