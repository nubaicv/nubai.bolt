<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bundle\Nubai;

use Bolt\Controller\Frontend as NubaiController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;

/**
 * Description of FrontendController
 *
 * @author ricardo
 */
class FrontendController extends NubaiController {

    /**
     * Controller for the "Homepage" route. Usually the front page of the website.
     *
     * @param Request $request
     *
     * @return TemplateResponse
     */
    public function homepage(Request $request) {

        // Codigo official do controlador homepage
        // ---------------------------------------------------------------------------------------------
        $homepage = $this->getOption('theme/homepage') ?: $this->getOption('general/homepage');
        $listingParameters = $this->getListingParameters($homepage, true);
        $content = $this->getContent($homepage, $listingParameters);

        $template = $this->templateChooser()->homepage($content);
        $globals = [];

        if (is_array($content) && count($content) > 0) {
            $first = current($content);
            $globals[$first->contenttype['slug']] = $content;
            $globals['records'] = $content;
        } elseif (is_object($content)) {
            $globals['record'] = $content;
            $globals[$content->contenttype['singular_slug']] = $content;
            $globals['records'] = [$content->id => $content];
        }
        // ---------------------------------------------------------------------------------------------
        // Meu codigo aqui
        // ---------------------------------------------------------------------------------------------

        $logged_in_message = $this->session()->has('customer_name') ? "We are logged in!" : "We are NOT logged in.";

        $data_to_template = [
            'template_data' => [
                'loggedin' => $logged_in_message,
                'attributes' => $this->session()->all(),
                'session_id' => $this->session()->getId(),
            ],
        ];


        // ---------------------------------------------------------------------------------------------



        return $this->render($template, $data_to_template, $globals);
    }

    /**
     * The listing page controller.
     *
     * @param Request $request         The Symfony Request
     * @param string  $contenttypeslug The content type slug
     *
     * @return TemplateResponse
     */
    public function listing(Request $request, $contenttypeslug) {

        // Codigo official do controlador listing
        // ---------------------------------------------------------------------------------------------
        $listingParameters = $this->getListingParameters($contenttypeslug);
        $content = $this->getContent($contenttypeslug, $listingParameters);
        $contenttype = $this->getContentType($contenttypeslug);

        $template = $this->templateChooser()->listing($contenttype);

        $globals = [
            'records' => $content,
            $contenttypeslug => $content,
            'contenttype' => $contenttype['name'],
        ];
        // ---------------------------------------------------------------------------------------------
        // Meu codigo aqui
        // ---------------------------------------------------------------------------------------------

        $logged_in_message = $this->session()->has('customer_name') ? "We are logged in!" : "We are NOT logged in.";

        $data_to_template = [
            'template_data' => [
                'loggedin' => $logged_in_message,
                'attributes' => $this->session()->all(),
                'session_id' => $this->session()->getId(),
            ],
        ];


        // ---------------------------------------------------------------------------------------------

        return $this->render($template, $data_to_template, $globals);
    }

    /**
     * Controller for a single record page, like '/page/about/' or '/entry/lorum'.
     *
     * @param Request $request         The request
     * @param string  $contenttypeslug The content type slug
     * @param string  $slug            The content slug
     *
     * @return TemplateResponse
     */
    public function record(Request $request, $contenttypeslug, $slug = '') {

        // Codigo official do controlador record
        // ---------------------------------------------------------------------------------------------
        $contenttype = $this->getContentType($contenttypeslug);

        if (isset($contenttype['viewless']) && $contenttype['viewless'] === true) {
            $this->abort(Response::HTTP_NOT_FOUND, "Page $contenttypeslug/$slug not found.");

            return null;
        }

        if (empty($slug)) {
            $slug = $request->get('id');
        }

        $slug = $this->app['slugify']->slugify($slug);

        $content = $this->getContent($contenttype['slug'], ['slug' => $slug, 'returnsingle' => true, 'log_not_found' => !is_numeric($slug)]);

        if (is_numeric($slug) && !$content) {
            $content = $this->getContent($contenttype['slug'], ['id' => $slug, 'returnsingle' => true]);
        }

        if (!$content) {
            $this->abort(Response::HTTP_NOT_FOUND, "Page $contenttypeslug/$slug not found.");

            return null;
        }

        $template = $this->templateChooser()->record($content);

        $this->app['editlink'] = $this->generateUrl('editcontent', ['contenttypeslug' => $contenttype['slug'], 'id' => $content->id]);
        $this->app['edittitle'] = $content->getTitle();

        $globals = [
            'record' => $content,
            $contenttype['singular_slug'] => $content,
        ];
        // ---------------------------------------------------------------------------------------------
        // Meu codigo aqui
        // ---------------------------------------------------------------------------------------------

        $logged_in_message = $this->session()->has('customer_name') ? "We are logged in!" : "We are NOT logged in.";

        $data_to_template = [
            'template_data' => [
                'loggedin' => $logged_in_message,
                'attributes' => $this->session()->all(),
                'session_id' => $this->session()->getId(),
            ],
        ];


        // ---------------------------------------------------------------------------------------------

        return $this->render($template, $data_to_template, $globals);
    }

    public function login(Request $request) {

        $this->session()->set('customer_name', 'Ricardo Ponce');


        $logged_in_message = $this->session()->has('customer_name') ? "We are logged in!" : "We are NOT logged in.";

        $data_to_template = [
            'template_data' => [
                'loggedin' => $logged_in_message,
                'attributes' => $this->session()->all(),
                'session_id' => $this->session()->getId(),
            ],
        ];

        return $this->render('mytemplate.twig', $data_to_template);
    }

    public function logout(Request $request) {

        $this->session()->invalidate();


        $logged_in_message = $this->session()->has('customer_name') ? "We are logged in!" : "We are NOT logged in.";

        $data_to_template = [
            'template_data' => [
                'loggedin' => $logged_in_message,
            ],
        ];

        return $this->render('mytemplate.twig', $data_to_template);
    }

// -------------------------------------------------------------------------

    public function testing(Request $request) {

//        $customer_data = [
//            'datecreated' => new \DateTime,
//            'datechanged' => new \DateTime,
//            'forename' => 'Arianne',
//            'surname' => 'Silva',
//            'email' => 'arianne.silva@nubai.com.cv',
//            'password' => '1234',
//            'activated' => 0,
//        ];
//        
//        $newcustomer = $this->storage()->create('customers', $customer_data);
//        
//        if ($this->storage()->save($newcustomer)) {
//            $result = $newcustomer;
//        } else {
//            $result = false;
//        }
        // Code 2
//        if ($newcustomer = $this->storage()->find('customers', 1)) {
//            
//            $newcustomer->setDatechanged(new \DateTime);
//            $newcustomer->setPhone('325204');
//            $this->storage()->delete($newcustomer);
//            $result = $newcustomer;
//        } else {
//            
//            $result = false;
//        }

        $repo = $this->storage()->getRepository('customers');
        
        $email = 'arianne.silva@nubai.com.cv';
        $password = '1234';
        
        $result = $repo->verifyCredentials($email, $password);

        $data_to_template = [
            'template_data' => [
                'title' => 'My title',
                'peo' => $result,
            ]
        ];

        return $this->render('testing.twig', $data_to_template);
    }

}
