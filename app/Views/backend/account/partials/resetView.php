<div class="card">
	<div class="card-header">
	    <h2 class="card-title text-center text-md-left">Reset Password</h2>
	</div>
	<div class="card-body">
        <div class="row">
            <div class="col-md-12 text-center">
            	<!-- Reset -->
    			<form method="post" id="reset_account" data-message="Do you want to reset your password?">
    				<input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
    				<button type="submit" class="btn btn-danger btn-sm">
    					 Reset Password
    				</button>
    			</form>
            	<?php if( ! is_null($currentSeller->identity->resetted_at)): ?>
					<span class="text-danger font-weight-bold">
						Hai richiesto il cambio password. Verifica la tua email e completa l'operazione. Hai tempo fino al 
						( da stabilire )
					</span>
            	<?php endif; ?>
            	<!-- End reset -->
            </div>
        </div>
    </div>
</div>