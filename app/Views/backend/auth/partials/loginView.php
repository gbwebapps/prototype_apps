<form id="auth_login" method="post">

	<!-- Token -->
	<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

	<div class="row">

		<!-- email Field -->
		<div class="col-md-12">
			<div class="form-group">
				<label for="email"><i class="fa-solid fa-circle-arrow-down"></i> Email</label>
				<input type="text" name="email" id="email" placeholder="Insert your email here..." class="form-control" autofocus value="gbwebapps@gmail.com">
				<div class="error_email text-danger font-weight-bold pt-1"></div>
			</div>
		</div>

		<!-- password Field -->
		<div class="col-md-12">
			<div class="form-group">
				<label for="password"><i class="fa-solid fa-circle-arrow-down"></i> Password</label>
				<input type="password" name="password" id="password" placeholder="Insert your password here..." class="form-control" value="password">
				<div class="error_password text-danger font-weight-bold pt-1"></div>
			</div>
		</div>
		
	</div>

	<div class="row">

		<!-- CheckBox Field -->
		<div class="col-md-6 text-left pt-2">
			<div class="form-check">
				<input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
				<label class="form-check-label" for="remember_me">
					Remember Me
				</label>
			</div>
		</div>

		<!-- Send Button -->
		<div class="col-md-6 text-right">
			<div class="form-group">
				<button class="btn btn-success btn-sm" type="submit">Send Data</button>
			</div>
		</div>

	</div>
	
</form>

<div class="text-center mt-5">
	<a href="<?= base_url('admin/auth/recovery'); ?>">Recovery Password</a>
</div>