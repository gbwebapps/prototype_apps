<?php declare(strict_types = 1); 

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SellersCheckMaster implements FilterInterface
{
    private $auth;

    public function __construct()
    {
        $this->auth = service('authorization')->currentSeller();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if($this->auth->identity->master !== '1'):
            if($request->isAJAX()):
                echo json_encode(['result' => false, 'message' => 'Non hai il ruolo per effettuare questa operazione!']); die();
            else:
                return redirect()->to(base_url('admin/dashboard'))->with('danger', 'Non hai il ruolo per effettuare questa operazione!');
            endif;
        endif;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}