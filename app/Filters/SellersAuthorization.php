<?php declare(strict_types = 1); 

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SellersAuthorization implements FilterInterface
{
    private $auth;

    public function __construct()
    {
        $this->auth = service('authorization');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if( ! $this->auth->currentSeller()):
            if($request->isAJAX()):
                echo json_encode(['result' => false]); die();
            else:
                return redirect()->to(base_url('admin/auth/login'));
            endif;
        endif;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}