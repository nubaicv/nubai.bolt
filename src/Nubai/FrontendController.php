<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bundle\Nubai;

use Bolt\Controller\Frontend as BoltController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;

/**
 * Description of FrontendController
 *
 * @author ricardo
 */
class FrontendController extends BoltController {

    public function helloWorld(Request $request) {
        return new Response('<h1>Hello World!<h1>');
    }

}
