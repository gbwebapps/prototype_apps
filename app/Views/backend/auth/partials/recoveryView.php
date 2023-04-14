<form id="auth_recovery" method="post">

	<!-- Token -->
	<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

	<div class="row">

		<!-- email Field -->
		<div class="col-md-12">
			<div class="form-group">
				<label for="email"><i class="fa-solid fa-circle-arrow-down"></i> Email</label>
				<input type="text" name="email" id="email" placeholder="Insert your email here..." class="form-control" autofocus>
				<div class="error_email text-danger font-weight-bold pt-1"></div>
			</div>
		</div>
		
	</div>

	<div class="row">

		<!-- Send Button -->
		<div class="col-md-12 text-center">
			<div class="form-group">
				<button class="btn btn-success btn-sm" type="submit">Send Data</button>
			</div>
		</div>

	</div>

</form>

<div class="text-center mt-5">
	<a href="<?= base_url('admin/auth/login'); ?>">Login</a>
</div>