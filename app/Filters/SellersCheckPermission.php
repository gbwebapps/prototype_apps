<?php declare(strict_types = 1); 

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SellersCheckPermission implements FilterInterface
{
    private $auth;

    public function __construct()
    {
        $this->auth = service('authorization')->currentSeller();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if(( ! isset($this->auth->permissions)) || ( ! in_array($arguments[1], $this->auth->permissions))):
            if($request->isAJAX()):
                echo json_encode(['result' => false, 'message' => 'Non hai il permesso per effettuare questa operazione!']); die();
            else:
                return redirect()->to(base_url('admin/' . $arguments[0]))->with('danger', 'Non hai il permesso per effettuare questa operazione!');
            endif;
        endif;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}