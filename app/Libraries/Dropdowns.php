<?php declare(strict_types = 1); 

namespace App\Libraries;

class Dropdowns {

	private $db;

	public function __construct()
	{
		$this->db = db_connect();
	}

	public function brands()
	{
		$builder = $this->db->table('brands');

		return $builder->select('id, brand')->orderBy('brand', 'asc')->get();
	}

	public function categories()
	{
		$builder = $this->db->table('categories');

		return $builder->select('id, category')->orderBy('category', 'asc')->get();
	}

	public function products()
	{
		$builder = $this->db->table('products');

		return $builder->select('id, product')->orderBy('product', 'asc')->get();
	}

	public function customers()
	{
		$builder = $this->db->table('customers');

		return $builder->select('id, customer')->orderBy('customer', 'asc')->get();
	}

	public function sellers()
	{
		$builder = $this->db->table('sellers');

		return $builder->select('id, (select concat(firstname, " ", lastname) from sellers as u where u.id = sellers.id) as identity')->where(['status' => '1'])->orderBy('identity', 'asc')->get();
	}

}