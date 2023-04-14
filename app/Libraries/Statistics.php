<?php declare(strict_types = 1); 

namespace App\Libraries;

class Statistics {

	private $db;

	public function __construct()
	{
		$this->db = db_connect();
	}

	public function basicStats(String $table): Int
	{
		$builder = $this->db->table($table);

		$items = $builder->countAllResults();

		return $items;
	}

}