<?= $this->extend('backend/template/mainView'); ?>

<?= $this->section('content'); ?>
	
	<div class="container mt-3">
		<div class="row">
			<div class="col-md-12">
				<div class="accordion" id="accordionTools">
					<div class="card">
					    <div class="card-header" id="headingCheckTable">
					        <h5 class="mb-0">
					            <a class="text-left" href="#" data-toggle="collapse" data-target="#collapseCheckTable" aria-expanded="true" aria-controls="collapseCheckTable">
					                <i class="fa-solid fa-magnifying-glass"></i> Check Table
					            </a>
					        </h5>
					    </div>
					    <div id="collapseCheckTable" class="collapse" aria-labelledby="headingCheckTable" data-parent="#accordionTools">
					        <div class="card-body">
					            <div id="repairTable">
					            	<?= $this->include('backend/tools/partials/checkTableView'); ?>
					            </div>
					        </div>
					    </div>
					</div>
				    <div class="card">
				        <div class="card-header" id="headingRepairTable">
				            <h5 class="mb-0">
				                <a class="text-left" href="#" data-toggle="collapse" data-target="#collapseRepairTable" aria-expanded="true" aria-controls="collapseRepairTable">
				                    <i class="fa-solid fa-hammer"></i> Repair Table
				                </a>
				            </h5>
				        </div>
				        <div id="collapseRepairTable" class="collapse" aria-labelledby="headingRepairTable" data-parent="#accordionTools">
				            <div class="card-body">
				                <div id="repairTable">
				                	<?= $this->include('backend/tools/partials/repairTableView'); ?>
				                </div>
				            </div>
				        </div>
				    </div>
				    <div class="card">
				        <div class="card-header" id="headingOptimizeTable">
				            <h5 class="mb-0">
				                <a class="text-left" href="#" data-toggle="collapse" data-target="#collapseOptimizeTable" aria-expanded="true" aria-controls="collapseOptimizeTable">
				                    <i class="fa-solid fa-bolt-lightning"></i> Optimize Table
				                </a>
				            </h5>
				        </div>
				        <div id="collapseOptimizeTable" class="collapse" aria-labelledby="headingOptimizeTable" data-parent="#accordionTools">
				            <div class="card-body">
				                <div id="optimizeTable">
				                	<?= $this->include('backend/tools/partials/optimizeTableView'); ?>
				                </div>
				            </div>
				        </div>
				    </div>
				    <div class="card">
				        <div class="card-header" id="headingOptimizeDatabase">
				            <h5 class="mb-0">
				                <a class="text-left" href="#" data-toggle="collapse" data-target="#collapseOptimizeDatabase" aria-expanded="true" aria-controls="collapseOptimizeDatabase">
				                    <i class="fa-solid fa-database"></i> Optimize Database
				                </a>
				            </h5>
				        </div>
				        <div id="collapseOptimizeDatabase" class="collapse" aria-labelledby="headingOptimizeDatabase" data-parent="#accordionTools">
				            <div class="card-body">
				                <div id="optimizeDatabase">
				                	<?= $this->include('backend/tools/partials/optimizeDatabaseView'); ?>
				                </div>
				            </div>
				        </div>
				    </div>
				    <div class="card">
				        <div class="card-header" id="headingBackup">
				            <h5 class="mb-0">
				                <a class="text-left" href="#" data-toggle="collapse" data-target="#collapseBackup" aria-expanded="true" aria-controls="collapseBackup">
				                    <i class="fa-solid fa-download"></i> Backup
				                </a>
				            </h5>
				        </div>
				        <div id="collapseBackup" class="collapse" aria-labelledby="headingBackup" data-parent="#accordionTools">
				            <div class="card-body">
				                <div id="backup">
				                	<?= $this->include('backend/tools/partials/backupView'); ?>
				                </div>
				            </div>
				        </div>
				    </div>
				    <div class="card">
				        <div class="card-header" id="headingManageBackups">
				            <h5 class="mb-0">
				                <a class="text-left" href="#" data-toggle="collapse" data-target="#collapseManageBackups" aria-expanded="true" aria-controls="collapseManageBackups">
				                    <i class="fa-solid fa-th-list"></i> Manage Backups
				                </a>
				            </h5>
				        </div>
				        <div id="collapseManageBackups" class="collapse" aria-labelledby="headingManageBackups" data-parent="#accordionTools">
				            <div class="card-body">
				                <div id="manageBackups">
				                	<?= $this->include('backend/tools/partials/manageBackupsView'); ?>
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection(); ?>