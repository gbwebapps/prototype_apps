<div class="row">
	<div class="col-md-6 offset-md-3">
		<div class="card mt-2">

			<form id="categories_edit" method="post">

				<!-- General Data -->
				<div class="card-header">
					<div class="row">
						<div class="col-md-12 text-center text-md-left py-2 py-md-0">
							<h2 class="card-title">General Data</h2>
						</div>
					</div>
				</div>

				<div class="card-body">

					<!-- Token -->
					<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

					<div class="row">

						<!-- category field -->
						<div class="col-md-12">
							<div class="form-group">
								<label for="category"><i class="fa-solid fa-circle-arrow-down"></i> Category</label>
								<input type="text" name="category" id="category" placeholder="Insert the category here..." class="form-control" value="<?= esc($category->category); ?>">
								<div class="error_category text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

						<!-- description field -->
						<div class="col-md-12">
							<div class="form-group">
								<label for="description"><i class="fa-solid fa-circle-arrow-down"></i> Description</label>
								<textarea name="description" id="description" rows="7" placeholder="Insert the description here..." class="form-control"><?= esc($category->description); ?></textarea>
								<div class="error_description text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>
				</div>
				<!-- end General Data -->

				<!-- Images -->
				<div class="card-header">
					<h2 class="card-title">Images</h2>
				</div>
				<div class="card-body">
					<div class="row">
							
						<!-- images field -->
						<div class="col-md-12 text-center">
							<div class="form-group">
								<label for="images">Images allowed max <?= config('Displaying')->upload_file_x; ?> x <?= config('Displaying')->upload_file_y; ?> - Max <?= (config('Displaying')->upload_file_size / 1048576); ?> Mb</label>
								<input type="file" name="images[]" id="images" multiple>
								<div class="error_images text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>
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
				
				<input type="hidden" name="id" value="<?= esc($category->id); ?>">
			</form>

		</div>
	</div>
</div>