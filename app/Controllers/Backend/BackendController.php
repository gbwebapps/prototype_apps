<?php

namespace App\Controllers\Backend;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BackendController
 *
 * BackendController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BackendController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BackendController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BackendController.
     *
     * @var array
     */
    protected $helpers = ['views', 'debug'];

    /**
     * The main data array
     * 
     * @var array
     */
    protected $data = [];

    /**
     * The authorization object
     *
     * @var object
     */
    protected $auth;

    /**
     * The current seller object
     *
     * @var object
     */
    protected $currentSeller;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        /* Calling authorization class */
        $this->auth = service('authorization');

        /* Collecting the current seller data */
        $this->currentSeller = $this->auth->currentSeller();

        /* The current seller data for the views */
        $this->data['currentSeller'] = $this->currentSeller;

        /* The Css Styles put before the App style. They are present in every controller. */
        $this->data['beforeStyle'] = [
            'Bootstrap CSS' => 'assets/css/bootstrap.min', 
            'Font Awesome CSS' => 'assets/css/all.min', 
            'jQuery UI CSS' => 'assets/css/jquery-ui.min', 
            'Toastr CSS' => 'assets/css/toastr.min'
        ];

        /* The Css Styles put after the App style. They are present in every controller. */
        $this->data['afterStyle'] = [];

        /* The Js Scripts put before the App script. They are present in every controller. */
        $this->data['beforeScript'] = [
            'jQuery JS' => 'assets/js/jQuery', 
            'jQuery UI JS' => 'assets/js/jquery-ui.min', 
            'Bootstrap JS' => 'assets/js/bootstrap.bundle.min', 
            'Toastr JS' => 'assets/js/toastr.min'
        ];

        /* The Js Scripts put after the App script. They are present in every controller. */
        $this->data['afterScript'] = [];
    }
}
