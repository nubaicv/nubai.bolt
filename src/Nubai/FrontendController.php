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

        $context = [
            'data' => [
                'title' => 'My title',
                'text1' => 'O primeiro texto',
                'text2' => 'O segunto texto',
            ]
        ];

        return $this->render('mytemplate.twig', $context);
    }

}
