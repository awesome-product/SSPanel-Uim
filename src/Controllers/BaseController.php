<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\View;
use App\Services\Auth;
use Smarty;
use Slim\Http\Response;

/**
 * BaseController
 */
class BaseController
{
    /**
     * @var Smarty
     */
    protected $view;

    /**
     * @var User
     */
    protected $user;

    /**
     * Construct page renderer
     */
    public function __construct()
    {
        $this->view = View::getSmarty();
        $this->user = Auth::getUser();
    }

    /**
     * Get smarty
     *
     * @return Smarty
     */
    public function view()
    {
        return $this->view;
    }

    /**
     * Write $res as json to response body
     *
     * @param mixed $res
     */
    public function echoJson(Response $response, $res): Response
    {
        $response->getBody()->write(json_encode($res));
        return $response;
    }
}
