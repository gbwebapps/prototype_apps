<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class ToolsController extends BackendController
{
    public function __construct()
    {
        $this->data['controller'] = 'tools';
        $this->data['entity'] = 'tools';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-tools';
        $this->data['title'] = 'Tools';

        $this->data['action'] = 'index';
        return view('backend/tools/indexView', $this->data);
    }
}
