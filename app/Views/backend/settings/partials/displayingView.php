<form method="post" class="settings_form">
    <legend>Images</legend><hr>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="upload_file_size"><i class="fa-solid fa-circle-arrow-down"></i> Images - Max size - <span class="text-info">Recommended 5Mb</span></label>
                <select name="upload_file_size" id="upload_file_size" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="1048576"<?= (esc($settings['upload_file_size']) === '1048576') ? ' selected' : null; ?>>1 Megabyte</option>
                    <option value="5242880"<?= (esc($settings['upload_file_size']) === '5242880') ? ' selected' : null; ?>>5 Megabyte</option>
                    <option value="10485760"<?= (esc($settings['upload_file_size']) === '10485760') ? ' selected' : null; ?>>10 Megabyte</option>
                    <option value="15728640"<?= (esc($settings['upload_file_size']) === '15728640') ? ' selected' : null; ?>>15 Megabyte</option>
                    <option value="20971520"<?= (esc($settings['upload_file_size']) === '20971520') ? ' selected' : null; ?>>20 Megabyte</option>
                    <option value="26214400"<?= (esc($settings['upload_file_size']) === '26214400') ? ' selected' : null; ?>>25 Megabyte</option>
                </select>
                <div class="error_upload_file_size text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="upload_file_x"><i class="fa-solid fa-circle-arrow-down"></i> Images - Max width - <span class="text-info">Recommended 3840px</span></label>
                <input type="text" name="upload_file_x" id="upload_file_x" class="form-control" value="<?= esc($settings['upload_file_x']); ?>" placeholder="Put here the max width...">
                <div class="error_upload_file_x text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="upload_file_y"><i class="fa-solid fa-circle-arrow-down"></i> Images - Max width - <span class="text-info">Recommended 2160px</span></label>
                <input type="text" name="upload_file_y" id="upload_file_y" class="form-control" value="<?= esc($settings['upload_file_y']); ?>" placeholder="Put here the max height...">
                <div class="error_upload_file_y text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="resize_medium_x"><i class="fa-solid fa-circle-arrow-down"></i> Images - Max medium width - <span class="text-info">Recommended 960px</span></label>
                <input type="text" name="resize_medium_x" id="resize_medium_x" class="form-control" value="<?= esc($settings['resize_medium_x']); ?>" placeholder="Put here the max medium width...">
                <div class="error_resize_medium_x text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="resize_medium_y"><i class="fa-solid fa-circle-arrow-down"></i> Images - Max medium height - <span class="text-info">Recommended 540px</span></label>
                <input type="text" name="resize_medium_y" id="resize_medium_y" class="form-control" value="<?= esc($settings['resize_medium_y']); ?>" placeholder="Put here the max medium height...">
                <div class="error_resize_medium_y text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="resize_small_x"><i class="fa-solid fa-circle-arrow-down"></i> Images - Max small width - <span class="text-info">Recommended 96px</span></label>
                <input type="text" name="resize_small_x" id="resize_small_x" class="form-control" value="<?= esc($settings['resize_small_x']); ?>" placeholder="Put here the max small width...">
                <div class="error_resize_small_x text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="resize_small_y"><i class="fa-solid fa-circle-arrow-down"></i> Images - Max small height - <span class="text-info">Recommended 54px</span></label>
                <input type="text" name="resize_small_y" id="resize_small_y" class="form-control" value="<?= esc($settings['resize_small_y']); ?>" placeholder="Put here the max small height...">
                <div class="error_resize_small_y text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group text-center">
                <label for="rename_images"><i class="fa-solid fa-circle-arrow-down"></i> Renaming Images</label>
                <input type="checkbox" name="rename_images" id="rename_images"<?= ($settings['rename_images'] == true) ? ' checked' : null; ?>>
                <div class="error_rename_images text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group text-center">
                <label for="overwrite_images"><i class="fa-solid fa-circle-arrow-down"></i> Overwriting Images</label>
                <input type="checkbox" name="overwrite_images" id="overwrite_images"<?= ($settings['overwrite_images'] == true) ? ' checked' : null; ?>>
                <div class="error_overwrite_images text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <legend>List</legend><hr>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="rows_per_list"><i class="fa-solid fa-circle-arrow-down"></i> Rows per list</label>
                <select name="rows_per_list" id="rows_per_list" class="form-control">
                    <option value="">-- Select an option --</option>
                    <option value="5"<?= ($settings['rows_per_list'] === '5') ? ' selected' : null; ?>>5</option>
                    <option value="10"<?= ($settings['rows_per_list'] === '10') ? ' selected' : null; ?>>10</option>
                    <option value="15"<?= ($settings['rows_per_list'] === '15') ? ' selected' : null; ?>>15</option>
                    <option value="20"<?= ($settings['rows_per_list'] === '20') ? ' selected' : null; ?>>20</option>
                    <option value="25"<?= ($settings['rows_per_list'] === '25') ? ' selected' : null; ?>>25</option>
                </select>
                <div class="error_rows_per_list text-danger font-weight-bold pt-1"></div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 text-center">
            <input type="hidden" name="section" value="displaying">
            <div class="error_section text-danger font-weight-bold pt-1"></div>
            <button type="submit" class="btn btn-success btn-sm">Send data</button>
        </div>
    </div>
</form>