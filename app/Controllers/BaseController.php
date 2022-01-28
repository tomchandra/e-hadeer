<?php

namespace App\Controllers;

use App\Models\M_Menu;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $session;
    protected $M_Menu;
    protected $menulist;
    protected $param_data;
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        //initial session
        $this->session  = \Config\Services::session();
        //start session
        $this->session->start();

        // initial reques
        $this->request  = \Config\Services::request();

        //initial model menu
        $this->M_Menu   = new M_Menu();
        // initial menu
        $this->param_data["menu"] = $this->M_Menu->app_getDataListMenu($this->session->get('user_id'));

        // initial url access
        $url = explode("/", uri_string(true));
        if (array_key_exists(1, $url)) {
            $this->session->set(["url_access" => $url["1"]]);
        }
    }
}
