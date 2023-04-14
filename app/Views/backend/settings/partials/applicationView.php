<form method="post" class="settings_form">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="application_name"><i class="fa-solid fa-circle-arrow-down"></i> Application name</label>
                <input type="text" name="application_name" id="application_name" class="form-control" value="<?= esc($settings['application_name']); ?>" placeholder="Put here the application name...">
                <div class="error_application_name text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="admin_email"><i class="fa-solid fa-circle-arrow-down"></i> Admin Email</label>
                <input type="text" name="admin_email" id="admin_email" class="form-control" value="<?= esc($settings['admin_email']); ?>" placeholder="Put here the admin email address...">
                <div class="error_admin_email text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 text-center">
            <input type="hidden" name="section" value="application">
            <div class="error_section text-danger font-weight-bold pt-1"></div>
            <button type="submit" class="btn btn-success btn-sm">Send data</button>
        </div>
    </div>
</form>