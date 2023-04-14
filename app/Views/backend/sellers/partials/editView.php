<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="card mt-2">

			<form id="sellers_edit" method="post">

				<!-- General Data -->
				<div class="card-header">
				    <h2 class="card-title text-center text-md-left">General Data</h2>
				</div>

				<div class="card-body">

					<!-- Token -->
					<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

					<div class="row">

						<!-- firstname field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="firstname"><i class="fa-solid fa-circle-arrow-down"></i> Firstname</label>
								<input type="text" name="firstname" id="firstname" placeholder="Insert the firstname here..." class="form-control" value="<?= esc($seller->firstname); ?>">
								<div class="error_firstname text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

						<!-- lastname field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="lastname"><i class="fa-solid fa-circle-arrow-down"></i> Lastname</label>
								<input type="text" name="lastname" id="lastname" placeholder="Insert the lastname here..." class="form-control" value="<?= esc($seller->lastname); ?>">
								<div class="error_lastname text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">

						<!-- email field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="email"><i class="fa-solid fa-circle-arrow-down"></i> Email</label>
								<input type="text" name="email" id="email" placeholder="Insert the email here..." class="form-control" value="<?= esc($seller->email); ?>">
								<div class="error_email text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

						<!-- phone field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone"><i class="fa-solid fa-circle-arrow-down"></i> Name</label>
								<input type="text" name="phone" id="phone" placeholder="Insert the phone here..." class="form-control" value="<?= esc($seller->phone); ?>">
								<div class="error_phone text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

					<div class="row">
						
						<!-- status field -->
						<div class="col-md-6">
							<div class="form-group">
								<label for="status"><i class="fa-solid fa-circle-arrow-down"></i> Status</label>
								<select name="status" id="status" class="form-control">
									<option value="">-- Select a Status --</option>
									<option value="0"<?= ($seller->status === '0') ? ' selected' : null; ?>>Unactive</option>
									<option value="1"<?= ($seller->status === '1') ? ' selected' : null; ?>>Active</option>
								</select>
								<div class="error_status text-danger font-weight-bold pt-1"></div>
							</div>
						</div>

					</div>

				</div>
				<!-- end General Data -->

				<!-- Images -->
				<div class="card-header">
					<h2 class="card-title text-center text-md-left">Images</h2>
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

				<!-- Permissions -->
				<div class="card-header">
				    <h2 class="card-title text-center text-md-left">Permissions</h2>
				</div>

				<div class="card-body">

				    <?php foreach(getPermssions() as $item): ?>

				        <div class="row permissions_box">
				            <div class="col-md-6 text-left">
				                <h5><i class="fa-solid <?= $item['icon']; ?>"></i> <?= ucfirst($item['title']); ?></h5>
				            </div>
				            <div class="col-md-6 text-right">
				                <a href="#" class="select_all" data-controller="<?= $item['controller']; ?>">Select all</a>
				            </div>
				            <div class="col-md-12">
				                <div class="row">
				                    
				                    <?php foreach($item['perms'] as $k => $v): ?>

				                        <div class="col-sm-6 col-md-4 col-lg-3 text-center py-1">
				                            <ul class="list-group list-group-flush">
				                                <li class="list-group-item">
				                                    <label for="<?= $k; ?>"><?= $v; ?></label>
				                                </li>
				                                <li class="list-group-item">
				                                    <input type="checkbox" class="<?= $item['controller']; ?>" name="permissions[]" value="<?= $k; ?>" id="<?= $k; ?>"<?= (in_array($k, $seller_perms)) ? ' checked' : null; ?>>
				                                </li>
				                            </ul>
				                        </div>

				                    <?php endforeach; ?>

				                </div>
				            </div>
				        </div>

				    <?php endforeach; ?>

				    <div class="row">
				        <div class="col-md-12">
				            <div class="error_permissions text-danger font-weight-bold pt-1"></div>
				        </div>
				    </div>

				</div> 
				<!-- end Permissions -->

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
				
				<input type="hidden" name="id" value="<?= esc($seller->id); ?>">
			</form>

		</div>
	</div>
</div>