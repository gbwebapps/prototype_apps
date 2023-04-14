<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 header_left">
            <h1 class="m-0">
                <i class="<?= $icon; ?>"></i> <?= $title; ?>
            </h1>
        </div>
        <div class="col-md-6 header_right">
            <?= (isset($options) ? $options : ''); ?>
        </div>
    </div>
</div>