<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Libraries\Statistics;

class DashboardController extends BackendController
{
    private $stats;

    public function __construct()
    {
        $this->stats = new Statistics;
        
        $this->data['controller'] = 'dashboard';
        $this->data['entity'] = 'dashboard';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-tachometer';
        $this->data['title'] = 'Dashboard';

        $this->data['action'] = 'index';
        return view('backend/dashboard/indexView', $this->data);
    }

    public function basicStats()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $modules = ['categories', 'brands', 'products', 'customers', 'orders', 'sellers'];

            foreach($modules as $module):

                $this->data[$module] = $this->stats->basicStats($module);

            endforeach;

            $output = view('backend/dashboard/partials/basicStatsview', $this->data);
            $json = ['output' => $output, 'token' => $token];

            return $this->response->setJSON($json); die();

        endif;
    }
}
