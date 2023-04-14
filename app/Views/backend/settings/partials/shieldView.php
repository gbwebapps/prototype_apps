<form method="post" class="settings_form">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="attempts_limit"><i class="fa-solid fa-circle-arrow-down"></i> Attempts limit</label>
                <select name="attempts_limit" id="attempts_limit" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="1"<?= (esc($settings['attempts_limit']) === '1') ? ' selected' : null; ?>>1</option>
                    <option value="2"<?= (esc($settings['attempts_limit']) === '2') ? ' selected' : null; ?>>2</option>
                    <option value="3"<?= (esc($settings['attempts_limit']) === '3') ? ' selected' : null; ?>>3</option>
                    <option value="4"<?= (esc($settings['attempts_limit']) === '4') ? ' selected' : null; ?>>4</option>
                    <option value="5"<?= (esc($settings['attempts_limit']) === '5') ? ' selected' : null; ?>>5</option>
                </select>
                <div class="error_attempts_limit text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="attempts_time"><i class="fa-solid fa-circle-arrow-down"></i> Attempts time</label>
                <select name="attempts_time" id="attempts_time" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="900"<?= (esc($settings['attempts_time']) === '900') ? ' selected' : null; ?>>15 minutes</option>
                    <option value="1800"<?= (esc($settings['attempts_time']) === '1800') ? ' selected' : null; ?>>30 minutes</option>
                    <option value="2700"<?= (esc($settings['attempts_time']) === '2700') ? ' selected' : null; ?>>45 minutes</option>
                    <option value="3600"<?= (esc($settings['attempts_time']) === '3600') ? ' selected' : null; ?>>1 Hour</option>
                    <option value="7200"<?= (esc($settings['attempts_time']) === '7200') ? ' selected' : null; ?>>2 Hours</option>
                </select>
                <div class="error_attempts_time text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="remember_me_time"><i class="fa-solid fa-circle-arrow-down"></i> Remember me time</label>
                <select name="remember_me_time" id="remember_me_time" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="86400"<?= (esc($settings['remember_me_time']) === '86400') ? ' selected' : null; ?>>24 hours</option>
                    <option value="172800"<?= (esc($settings['remember_me_time']) === '172800') ? ' selected' : null; ?>>2 days</option>
                    <option value="432000"<?= (esc($settings['remember_me_time']) === '432000') ? ' selected' : null; ?>>5 days</option>
                    <option value="864000"<?= (esc($settings['remember_me_time']) === '864000') ? ' selected' : null; ?>>10 days</option>
                </select>
                <div class="error_remember_me_time text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="session_time"><i class="fa-solid fa-circle-arrow-down"></i> Session time</label>
                <select name="session_time" id="session_time" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="10800"<?= (esc($settings['session_time']) === '10800') ? ' selected' : null; ?>>3 hours</option>
                    <option value="21600"<?= (esc($settings['session_time']) === '21600') ? ' selected' : null; ?>>6 hours</option>
                    <option value="43200"<?= (esc($settings['session_time']) === '43200') ? ' selected' : null; ?>>12 hours</option>
                    <option value="86400"<?= (esc($settings['session_time']) === '86400') ? ' selected' : null; ?>>24 hours</option>
                </select>
                <div class="error_session_time text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="activation_time"><i class="fa-solid fa-circle-arrow-down"></i> Activation time</label>
                <select name="activation_time" id="activation_time" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="10800"<?= (esc($settings['activation_time']) === '10800') ? ' selected' : null; ?>>3 hours</option>
                    <option value="21600"<?= (esc($settings['activation_time']) === '21600') ? ' selected' : null; ?>>6 hours</option>
                    <option value="43200"<?= (esc($settings['activation_time']) === '43200') ? ' selected' : null; ?>>12 hours</option>
                    <option value="86400"<?= (esc($settings['activation_time']) === '86400') ? ' selected' : null; ?>>24 hours</option>
                </select>
                <div class="error_activation_time text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 text-center">
            <input type="hidden" name="section" value="shield">
            <div class="error_section text-danger font-weight-bold pt-1"></div>
            <button type="submit" class="btn btn-success btn-sm">Send data</button>
        </div>
    </div>
</form>