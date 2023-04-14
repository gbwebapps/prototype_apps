<?php declare(strict_types = 1); 

namespace App\Controllers\Components;

use CodeIgniter\Controller;
use App\Libraries\Components\TokensManager;

class TokensController extends Controller
{
	private $tokens;

	public function show()
	{
		if($this->request->isAJAX()):

			$this->data['posts'] = $this->request->getPost();

			$token = csrf_hash();

			$this->tokens = new TokensManager();

			$this->data['data'] = $this->tokens->show($this->data['posts']);
			$this->data['class'] = 'tokensPagination';
			
			$output = view('components/tokens/tokensView', $this->data);
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

			$this->tokens = new TokensManager();

			$rules = $this->tokens->validateRules();

			if( ! $this->validate($rules)):

				$json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in deleting token...'];
				return $this->response->setJSON($json); die();

			else:

				$id = $this->request->getPost('id');

				if($this->tokens->delete($id)):
					$json = ['result' => true, 'token' => $token, 'message' => 'Token has been deleted successfully!'];
				else:
					$json = ['result' => false, 'token' => $token, 'message' => 'Token deleting failed!'];
				endif;

				return $this->response->setJSON($json); die();

			endif;

		else:
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		endif;
	}

}
