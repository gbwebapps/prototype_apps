<?php

namespace App\Controllers\Components;

use CodeIgniter\Controller;
use App\Libraries\Components\GalleryOneManager;

class GalleryOneController extends Controller
{
	private $galleryOne;
	private $auth;

	public function __construct()
	{
		$this->auth = service('authorization');

		helper(['views', 'debug', 'text']);
	}

	public function show()
	{
		if($this->request->isAJAX()):

			$token = csrf_hash();

			$this->data['id'] = $this->request->getPost('id');
			$this->data['view'] = $this->request->getPost('view');
			$this->data['entity'] = $this->request->getPost('entity');

			$this->galleryOne = new GalleryOneManager($this->data['entity']);

			$this->data['images'] = $this->galleryOne->show($this->data['id']);

			$output = view('components/galleryOne/galleryOneView', $this->data);
			$json = ['output' => $output, 'token' => $token];

			return $this->response->setJSON($json); die();

		else:
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		endif;
	}

	public function delete()
	{
		if($this->request->isAJAX()):

			$token = csrf_hash();

			$id = $this->request->getPost('id');
			$entity = $this->request->getPost('entity');
			$controller = $this->request->getPost('controller');

			$this->galleryOne = new GalleryOneManager($entity);

			if($this->galleryOne->delete($id)):

				$json = ['result' => true, 'token' => $token, 'message' => 'Image has been deleted successfully!'];

				if($controller === 'account'):
					$this->data['currentSeller'] = $this->auth->currentSeller();
					$this->data['controller'] = 'account';
					$json['menuTop'] = view('backend/template/menuTopView', $this->data);
				endif;

			else:
				$json = ['result' => false, 'token' => $token, 'output' => 'Image deleting failed!'];
			endif;

			return $this->response->setJSON($json); die();

		else:
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		endif;
	}

	public function setCover()
	{
		if($this->request->isAJAX()):

			$token = csrf_hash();

			$id = $this->request->getPost('id');
			$entityid = $this->request->getPost('entityid');
			$entity = $this->request->getPost('entity');
			$controller = $this->request->getPost('controller');

			$this->galleryOne = new GalleryOneManager($entity);

			if($this->galleryOne->setCover($id, $entityid)):

				$json = ['result' => true, 'token' => $token, 'message' => 'Cover has been setted successfully!'];
				
				if($controller === 'account'):
					$this->data['currentSeller'] = $this->auth->currentSeller();
					$this->data['controller'] = 'account';
					$json['menuTop'] = view('backend/template/menuTopView', $this->data);
				endif;

			else:
				$json = ['result' => false, 'token' => $token, 'output' => 'Cover setting failed!'];
			endif;

			return $this->response->setJSON($json); die();

		else:
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		endif;
	}

	public function removeCover()
	{
		if($this->request->isAJAX()):

			$token = csrf_hash();

			$id = $this->request->getPost('id');
			$entityid = $this->request->getPost('entityid');
			$entity = $this->request->getPost('entity');
			$controller = $this->request->getPost('controller');

			$this->galleryOne = new GalleryOneManager($entity);

			if($this->galleryOne->removeCover($id, $entityid)):

				$json = ['result' => true, 'token' => $token, 'message' => 'Cover has been removed successfully!'];

				if($controller === 'account'):
					$this->data['currentSeller'] = $this->auth->currentSeller();
					$this->data['controller'] = 'account';
					$json['menuTop'] = view('backend/template/menuTopView', $this->data);
				endif;

			else:
				$json = ['result' => false, 'token' => $token, 'output' => 'Cover removing failed!'];
			endif;

			return $this->response->setJSON($json); die();

		else:
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		endif;
	}
}
