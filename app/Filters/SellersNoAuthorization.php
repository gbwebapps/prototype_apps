<?php declare(strict_types = 1); 

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SellersNoAuthorization implements FilterInterface
{
    private $auth;

    public function __construct()
    {
        $this->auth = service('authorization');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if($this->auth->currentSeller()):
            return redirect()->to(session('_ci_previous_url'));
        endif;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}