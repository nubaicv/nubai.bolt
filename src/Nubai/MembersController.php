<?php

namespace Bundle\Nubai;

use Bolt\Controller\Frontend;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;

/**
 * Description of MembersController
 *
 * @author ricardo
 */
class MembersController extends Frontend {

    public function addRoutes(ControllerCollection $c) {

        $c->match('/members', [$this, 'members']);

        return $c;
    }

    public function members(Request $request) {
        
        return $this->redirectToRoute('homepage');

//        return $this->render('members.twig', []);
    }
}
