<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Libraries\Token;

class Authorization {

	private $db;
	private $session;

	public function __construct()
	{
		$this->db = db_connect();
		$this->session = session();
	}

	public function currentSeller()
	{
		if($data = $this->getSellerFromSession()):
			return $data;
		endif;

		if($data = $this->getSellerFromCookie()):
			return $data;
		endif;

		return false;
	}

	private function getSellerFromSession()
	{
		if(( ! $this->session->backend_session) || ($this->session->backend_session === '')):
			return false;
		endif;

		$token = new Token($this->session->backend_session);
		$token_hash = $token->getHash();

		$builder = $this->db->table('sellers_tokens');
		$query = $builder->select('*')->limit(1)->getWhere(['token_hash' => $token_hash, 'token_type' => 'session']);

		if($query->getRow('token_hash') && $query->getRow('token_expire') > date('Y-m-d H:i:s')):

			$data = $this->getSeller(['id' => $query->getRow('seller_id'), 'status' => '1', 'suspended_at' => null]);

			if($data): return $data; endif;

		endif;

		return false;
	}

	private function getSellerFromCookie()
	{
		$request = service('request');

		$cookie = $request->getCookie('remember_me');

		if(is_null($cookie)): return false; endif;

		$token = new Token($cookie);
		$token_hash = $token->getHash();

		$builder = $this->db->table('sellers_tokens');
		$query = $builder->select('*')->limit(1)->getWhere(['token_hash' => $token_hash, 'token_type' => 'cookie']);

		if($query->getRow('token_hash') && $query->getRow('token_expire') > date('Y-m-d H:i:s')):

			$data = $this->getSeller(['id' => $query->getRow('seller_id'), 'status' => '1', 'suspended_at' => null]);

			if($data): return $data; endif;

		endif;

		return false;
	}

	private function getSeller(Array $where)
	{
		$data = new \stdClass();

		$builder = $this->db->table('sellers');

		if($data->identity = $builder->select('
			sellers.id, 
			(select images.url from images where images.entity_id = sellers.id and images.entity = "sellers" and images.is_cover = "1") as image, 
			sellers.firstname, 
			sellers.lastname, 
			sellers.email, 
			sellers.phone, 
			sellers.status, 
			sellers.master, 
			sellers.created_at, 
			sellers.updated_at, 
			sellers.suspended_at, 
			sellers.resetted_at
		')->limit(1)->getWhere($where)->getRow()):

			$permissions = $builder->select('permission')->join('sellers_permissions', 'sellers_permissions.seller_id = sellers.id')->getWhere(['sellers_permissions.seller_id' => $data->identity->id])->getResult();

			foreach($permissions as $v):
				$data->permissions[] = $v->permission;
			endforeach;

			return $data;

		endif;

		return false;
	}
	
}