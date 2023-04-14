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
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Category</li>
                            <li class="list-group-item font-weight-bold"><?= esc($category->category); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-circle-arrow-down"></i> Description</li>
                            <li class="list-group-item font-weight-bold">
                                <?= esc($category->description); ?>
                            </li>
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
                            <li class="list-group-item"><?= esc($category->created_at); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Created by</li>
                            <li class="list-group-item"><?= esc($category->created); ?></li>
                        </ul>
                    </div>
                </div>

                <?php if( ! is_null($category->updated_at) && ! is_null($category->updated_by)): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated at</li>
                                <li class="list-group-item"><?= esc($category->updated_at); ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Updated by</li>
                                <li class="list-group-item"><?= esc($category->updated); ?></li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <!-- end Meta tags -->

        </div>
    </div>
</div>
<div id="id" data-value="<?= esc($category->id); ?>">