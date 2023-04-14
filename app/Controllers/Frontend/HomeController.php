<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class HomeController extends FrontendController
{
    public function index()
    {
        return view('frontend/home/indexView');
    }
}
