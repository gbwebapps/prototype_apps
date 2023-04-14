<?php 

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Displaying extends BaseConfig {

	public $upload_file_size = 5242880;

	public $upload_file_x = 3840;

	public $upload_file_y = 2160;

	public $resize_medium_x = 960;

	public $resize_medium_y = 540;

	public $resize_small_x = 96;

	public $resize_small_y = 54;

	public $rename_images = 0;

	public $overwrite_images = 0;

	public $rows_per_list = 5;

}