<div class="list-group mb-3 mb-md-0">
	<a href="<?= base_url('admin/account/edit'); ?>" class="list-group-item list-group-item-action<?= ($action === 'edit_account') ? ' active' : null; ?>">
		Edit Data
	</a>
	<a href="<?= base_url('admin/account/permissions'); ?>" class="list-group-item list-group-item-action<?= ($action === 'permissions') ? ' active' : null; ?>">
		Permissions
	</a>
	<a href="<?= base_url('admin/account/reset'); ?>" class="list-group-item list-group-item-action<?= ($action === 'reset') ? ' active' : null; ?>">
		Reset Password
	</a>
	<a href="<?= base_url('admin/account/images'); ?>" class="list-group-item list-group-item-action<?= ($action === 'images') ? ' active' : null; ?>">
		Images
	</a>
	<a href="<?= base_url('admin/account/tokens'); ?>" class="list-group-item list-group-item-action<?= ($action === 'tokens') ? ' active' : null; ?>">
		Tokens
	</a>
</div>