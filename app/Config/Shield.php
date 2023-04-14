<?php 

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Shield extends BaseConfig {

	public $attempts_limit = 3;

	public $attempts_time = 3600;

	public $remember_me_time = 864000;

	public $session_time = 86400;

	public $activation_time = 21600;

}