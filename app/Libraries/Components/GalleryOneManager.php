<?php declare(strict_types=1);

namespace App\Libraries\Components;

class GalleryOneManager {

	private $entity;
	private $db;
	
	public function __construct(String $entity)
	{
		$this->entity = $entity;
		$this->db = db_connect();
	}

	public function show(String $id): Array|String|Bool
	{
		$builder = $this->db->table('images');
		$query = $builder->orderBy('is_cover', 'desc')->getWhere(['entity_id' => $id, 'entity' => $this->entity]);

		return $query->getResult();
	}

	public function delete(Int $id): Bool
	{
		$builder = $this->db->table('images');
		$image = $builder->select('url')->getWhere(['id' => $id])->getRow();

		if($builder->delete(['id' => $id])):
			$imageSizes = array('large', 'medium', 'small');

			foreach($imageSizes as $size):
				if(file_exists('./images/' . $this->entity . '/' . $size . '/' . $image->url)):
					unlink('./images/' . $this->entity . '/' . $size . '/' . $image->url);
				endif;
			endforeach;

			return true;
		endif;

		return false;
	}

	public function setCover(Int $id, String $entityid): Bool
	{
		$builder = $this->db->table('images');

		$this->db->transBegin();

		$builder->update(['is_cover' => '0'], ['entity_id' => $entityid, 'entity' => $this->entity]);
		$builder->update(['is_cover' => '1'], ['id' => $id]);

		if ($this->db->transStatus() === false):
			$this->db->transRollback();
			return false;
		else:
			$this->db->transCommit();
			return true;
		endif;
	}

	public function removeCover(Int $id, String $entityid): Bool
	{
		$builder = $this->db->table('images');

		if ($builder->update(['is_cover' => '0'], ['id' => $id, 'entity_id' => $entityid, 'entity' => $this->entity])):
			return true;
		else:
			return false;
		endif;
	}

}