<form id="auth_set_password" method="post">

	<!-- Token -->
	<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

	<div class="row">

		<!-- password Field -->
		<div class="col-md-12">
			<div class="form-group">
				<label for="password"><i class="fa-solid fa-circle-arrow-down"></i> New Password</label>
				<input type="password" name="password" id="password" placeholder="Insert your new password here..." class="form-control" autofocus>
				<div class="error_password text-danger font-weight-bold pt-1"></div>
			</div>
		</div>

		<!-- confirm_password Field -->
		<div class="col-md-12">
			<div class="form-group">
				<label for="confirm_password"><i class="fa-solid fa-circle-arrow-down"></i> Confirm Password</label>
				<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your new password here..." class="form-control">
				<div class="error_confirm_password text-danger font-weight-bold pt-1"></div>
			</div>
		</div>
		
	</div>

	<div class="row">

		<!-- Send Button -->
		<div class="col-md-12 text-center">
			<div class="form-group">
				<!-- code Hidden Field -->
				<input type="hidden" name="code" value="<?= $code; ?>">
				<div class="error_code text-danger font-weight-bold text-center"></div>
				<button class="btn btn-success btn-sm" type="submit">Send Data</button>
			</div>
		</div>

	</div>
	
</form>

<div class="text-center mt-5">
	<a href="<?= base_url('admin/auth/login'); ?>">Login</a> 
	&bull; 
	<a href="<?= base_url('admin/auth/recovery'); ?>">Recovery Password</a>
</div>