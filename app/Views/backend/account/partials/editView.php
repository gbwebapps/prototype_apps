<div class="card">
	<div class="card-header">
	    <h2 class="card-title text-center text-md-left">Edit Account</h2>
	</div>

	<form id="edit_account" method="post">

		<!-- General Data -->
		<div class="card-body">

			<!-- Token -->
			<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

			<div class="row">

				<!-- firstname field -->
				<div class="col-md-6">
					<div class="form-group">
						<label for="firstname"><i class="fa-solid fa-circle-arrow-down"></i> Firstname</label>
						<input type="text" name="firstname" id="firstname" placeholder="Insert the firstname here..." class="form-control" value="<?= esc($currentSeller->identity->firstname); ?>">
						<div class="error_firstname text-danger font-weight-bold pt-1"></div>
					</div>
				</div>

				<!-- lastname field -->
				<div class="col-md-6">
					<div class="form-group">
						<label for="lastname"><i class="fa-solid fa-circle-arrow-down"></i> Lastname</label>
						<input type="text" name="lastname" id="lastname" placeholder="Insert the lastname here..." class="form-control" value="<?= esc($currentSeller->identity->lastname); ?>">
						<div class="error_lastname text-danger font-weight-bold pt-1"></div>
					</div>
				</div>

			</div>

			<div class="row">

				<!-- email field -->
				<div class="col-md-6">
					<div class="form-group">
						<label for="email"><i class="fa-solid fa-circle-arrow-down"></i> Email</label>
						<input type="text" name="email" id="email" placeholder="Insert the email here..." class="form-control" value="<?= esc($currentSeller->identity->email); ?>">
						<div class="error_email text-danger font-weight-bold pt-1"></div>
					</div>
				</div>

				<!-- phone field -->
				<div class="col-md-6">
					<div class="form-group">
						<label for="phone"><i class="fa-solid fa-circle-arrow-down"></i> Name</label>
						<input type="text" name="phone" id="phone" placeholder="Insert the phone here..." class="form-control" value="<?= esc($currentSeller->identity->phone); ?>">
						<div class="error_phone text-danger font-weight-bold pt-1"></div>
					</div>
				</div>

			</div>

			<div class="row">

			    <!-- Status -->
			    <?php 
			        if($currentSeller->identity->status === '1'):
			            $text = 'Active';
			            $class = ' text-success font-weight-bold';
			        elseif($currentSeller->identity->status === '0'):
			            $text = 'Unactive';
			            $class = ' text-danger font-weight-bold';
			        endif; 
			    ?>

			    <div class="col-md-6">
			        <ul class="list-group list-group-flush">
			            <li class="list-group-item font-weight-bold"><i class="fa-solid fa-circle-arrow-down"></i> Status</li>
			            <li class="list-group-item<?= $class; ?>"><?= esc($text); ?></li>
			        </ul>
			    </div>
			    <!-- End status -->

			</div>

		</div>
		<!-- end General Data -->

		<div class="card-footer text-center">
			<button id="refresh_account" type="button" class="btn btn-danger btn-sm" data-message="Are you sure to refresh the form?">Refresh</button>
		    <button type="submit" class="btn btn-success btn-sm">Send Data</button>
		</div>
	</form>
</div>