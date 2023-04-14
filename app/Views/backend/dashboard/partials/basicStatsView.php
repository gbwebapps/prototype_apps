<div class="jumbotron mb-4 pb-0">

	<div class="row">

		<div class="col-md-6 col-lg-4">
			<!-- small card -->
			<div class="small-box bg-danger">
				<div class="inner">
					<div class="row align-items-center">
						<div class="col h1 text-left">
							Categories
						</div>
						<div class="col h1 text-right">
							<?= esc($categories); ?>
						</div>
					</div>
				</div>
				<div class="icon"><i class="nav-icon fa-solid fa-diagram-project"></i></div>
				<a href="<?= base_url('admin/categories'); ?>" class="small-box-footer">
					Go To Categories Data <i class="fa-solid fa-chart-simple"></i>
				</a>
			</div>
		</div>

		<div class="col-md-6 col-lg-4">
			<!-- small card -->
			<div class="small-box bg-success">
				<div class="inner">
					<div class="row align-items-center">
						<div class="col h1 text-left">
							Brands
						</div>
						<div class="col h1 text-right">
							<?= esc($brands); ?>
						</div>
					</div>
				</div>
				<div class="icon"><i class="nav-icon fa-solid fa-industry"></i></div>
				<a href="<?= base_url('admin/brands'); ?>" class="small-box-footer">
					Go To Brands Data <i class="fa-solid fa-chart-simple"></i>
				</a>
			</div>
		</div>

		<div class="col-md-6 col-lg-4 text-white">
			<!-- small card -->
			<div class="small-box bg-warning">
				<div class="inner">
					<div class="row align-items-center">
						<div class="col h1 text-left">
							Products
						</div>
						<div class="col h1 text-right">
							<?= esc($products); ?>
						</div>
					</div>
				</div>
				<div class="icon"><i class="nav-icon fa-solid fa-gifts"></i></div>
				<a href="<?= base_url('admin/products'); ?>" class="small-box-footer">
					Go To Products Data <i class="fa-solid fa-chart-simple"></i>
				</a>
			</div>
		</div>

		<div class="col-md-6 col-lg-4">
			<!-- small card -->
			<div class="small-box bg-primary">
				<div class="inner text-white">
					<div class="row align-items-center">
						<div class="col h1 text-left">
							Customers
						</div>
						<div class="col h1 text-right">
							<?= esc($customers); ?>
						</div>
					</div>
				</div>
				<div class="icon"><i class="nav-icon fa-solid fa-person-walking"></i></div>
				<a href="<?= base_url('admin/customers'); ?>" class="small-box-footer">
					Go To Customers Data <i class="fa-solid fa-chart-simple"></i>
				</a>
			</div>
		</div>

		<div class="col-md-6 col-lg-4">
			<!-- small card -->
			<div class="small-box bg-secondary text-white">
				<div class="inner">
					<div class="row align-items-center">
						<div class="col h1 text-left">
							Orders
						</div>
						<div class="col h1 text-right">
							<?= esc($orders); ?>
						</div>
					</div>
				</div>
				<div class="icon"><i class="nav-icon fa-solid fa-file-signature"></i></div>
				<a href="<?= base_url('admin/orders'); ?>" class="small-box-footer">
					Go To Orders Data <i class="fa-solid fa-chart-simple"></i>
				</a>
			</div>
		</div>

		<div class="col-md-6 col-lg-4">
			<!-- small card -->
			<div class="small-box bg-info">
				<div class="inner">
					<div class="row align-items-center">
						<div class="col h1 text-left">
							Sellers
						</div>
						<div class="col h1 text-right">
							<?= esc($sellers); ?>
						</div>
					</div>
				</div>
				<div class="icon"><i class="nav-icon fa-solid fa-users"></i></div>
				<a href="<?= base_url('admin/sellers'); ?>" class="small-box-footer">
					Go To Sellers Data <i class="fa-solid fa-chart-simple"></i>
				</a>
			</div>
		</div>

	</div>

</div>