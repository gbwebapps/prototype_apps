<div class="card">
	<div class="card-header">
	    <h2 class="card-title text-center text-md-left">Images</h2>
	</div>

	<div class="card-body">
	    <div class="showGalleryOne"></div>
	</div>
	
	<!-- Images -->
	<div class="card-body">
		<div class="row">
				
			<!-- images field -->
			<div class="col-md-12 text-center">
				<form id="images_account" method="post">
					<div class="form-group">
						<label for="images">Images allowed max <?= config('Displaying')->upload_file_x; ?> x <?= config('Displaying')->upload_file_y; ?> - Max <?= (config('Displaying')->upload_file_size / 1048576); ?> Mb</label>
						<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
						<input type="file" name="images[]" id="images" multiple>
						<div class="error_images text-danger font-weight-bold pt-1"></div>
					</div>
				</form>
			</div>

		</div>
	</div> 
	<!-- end Images -->

	<div class="card-footer text-center">
	    <button type="submit" form="images_account" class="btn btn-success btn-sm">Upload</button>
	</div>
</div>