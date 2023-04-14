<form method="post" class="settings_form">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group text-center">
                <label for="log_file"><i class="fa-solid fa-circle-arrow-down"></i> Write the flow file</label>
                <input type="checkbox" name="log_file" id="log_file"<?= ($settings['log_file'] == true) ? ' checked' : null; ?>>
                <div class="error_log_file text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group text-center">
                <label for="benchmark"><i class="fa-solid fa-circle-arrow-down"></i> Enable the benchmarks</label>
                <input type="checkbox" name="benchmark" id="benchmark"<?= ($settings['benchmark'] == true) ? ' checked' : null; ?>>
                <div class="error_benchmark text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group text-center">
                <label for="service"><i class="fa-solid fa-circle-arrow-down"></i> Maintenance mode</label>
                <input type="checkbox" name="service" id="service"<?= ($settings['service'] == true) ? ' checked' : null; ?>>
                <div class="error_service text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 text-center">
            <input type="hidden" name="section" value="debug">
            <div class="error_section text-danger font-weight-bold pt-1"></div>
            <button type="submit" class="btn btn-success btn-sm">Send data</button>
        </div>
    </div>
</form>