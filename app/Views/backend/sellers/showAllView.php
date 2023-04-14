<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container-fluid">
		
		<!-- Token -->
		<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

		<div class="row">

			<!-- Search bar -->
			<div class="col-md-12" id="searchBar">
				<div class="row pt-3 pt-md-0">
					<div class="col-md-2">
						<div class="form-group">
							<label for="firstname">Search by Firstname</label>
							<div class="input-group">
								<input type="text" class="form-control" id="firstname" autocomplete="off" placeholder="Search by Firstname...">
							    <div class="input-group-append">
									<div class="input-group-text resetSearchFields"><i class="fa fa-times"></i></div>
							    </div>
							</div>
							<div class="error_firstname text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="lastname">Search by Lastname</label>
							<div class="input-group">
								<input type="text" class="form-control" id="lastname" autocomplete="off" placeholder="Search by Lastname...">
							    <div class="input-group-append">
									<div class="input-group-text resetSearchFields"><i class="fa fa-times"></i></div>
							    </div>
							</div>
							<div class="error_lastname text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="email">Search by Email</label>
							<div class="input-group">
								<input type="text" class="form-control" id="email" autocomplete="off" placeholder="Search by Email...">
							    <div class="input-group-append">
									<div class="input-group-text resetSearchFields"><i class="fa fa-times"></i></div>
							    </div>
							</div>
							<div class="error_email text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="phone">Search by Phone</label>
							<div class="input-group">
								<input type="text" class="form-control" id="phone" autocomplete="off" placeholder="Search by Phone...">
							    <div class="input-group-append">
									<div class="input-group-text resetSearchFields"><i class="fa fa-times"></i></div>
							    </div>
							</div>
							<div class="error_phone text-danger font-weight-bold pt-1"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="d-none d-md-block">&nbsp;</label>
							<button type="button" class="btn btn-primary btn-block" id="linkResetSearch">
								Reset Search
							</button>
						</div>
					</div>
				</div>
			</div>
			<!-- End search bar -->

			<!-- Main data -->
			<div class="col-md-12">
				<div id="showData"></div>
			</div>
			<!-- End main Data -->

		</div>
	</div>

<?= $this->endSection(); ?>