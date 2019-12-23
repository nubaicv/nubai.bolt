<?php

namespace Bundle\Nubai;

use Bolt\Controller\Frontend as NubaiController;
use Bolt\Helpers\Input;
use Bolt\Translation\Translator as Trans;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;

/**
 * Description of FrontendController
 *
 * @author ricardo
 */
class FrontendController extends NubaiController {

    public function __construct() {
        
    }

    /**
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

        if (!$request->hasPreviousSession() === true) {
            $this->session()->set('hit', true);
        } else {
            $this->session()->set('hit', false);
        }

        $this->session()->set('session_id', $this->session()->getId());
        $this->session()->set('remote_server', $request->server->get('REMOTE_ADDR'));
        $some_data['attributes'] = $this->session()->all();

        $data_to_template = [
            'some_data' => $some_data
        ];


        // ---------------------------------------------------------------------------------------------
        return $this->render($template, $data_to_template, $globals);
    }

    public function members(Request $request) {

        if ($this->session()->has('customer') === true) {

            return $this->redirectToRoute('homeproducts');
        }

        $data_to_template = [
        ];

        return $this->render('members.twig', $data_to_template);
    }

    public function login(Request $request) {

        if ($this->session()->has('customer') === true) {

            $this->session()->invalidate();
            return $this->redirectToRoute('homepage');
        }

        $email = $request->request->filter('email', null, FILTER_SANITIZE_EMAIL);
        $password = $request->request->filter('password', null, FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH]);

        $repo = $this->storage()->getRepository('customers');

        $customer = $repo->verifyCredentials($email, $password);

        if ($customer) {

            $this->session()->set('customer', $customer);
            $this->session()->getFlashBag()->add('success', 'members:login:loggedin');
        } else {
            
            $this->session()->getFlashBag()->add('error', 'members:login:loginfailed');
            return $this->redirectToRoute('memberspage');
        }

        return $this->redirectToRoute('homepage');
    }

    public function register(Request $request) {

        if ($this->session()->has('customer') === true) {

            $this->session()->invalidate();
            return $this->redirectToRoute('homepage');
        }

        if (!$request->request->filter('email', null, FILTER_VALIDATE_EMAIL)) {

            $this->session()->getFlashBag()->add('error', 'members:register:invalidemail');
            return $this->redirectToRoute('memberspage');
        }
        $email = $request->request->filter('email', null, FILTER_SANITIZE_EMAIL);

        if (!$request->request->filter('password', null, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/']])) {

            $this->session()->getFlashBag()->add('error', 'members:register:invalidpassword');
            return $this->redirectToRoute('memberspage');
        }


        $forename = $request->request->filter('forename', null, FILTER_SANITIZE_STRING);
        $surname = $request->request->filter('surname', null, FILTER_SANITIZE_STRING);
        $password = password_hash($request->request->filter('password', null, FILTER_SANITIZE_STRING), PASSWORD_BCRYPT) ;
        $address = $request->request->filter('address', null, FILTER_SANITIZE_STRING);
        $phone = $request->request->filter('phone', null, FILTER_SANITIZE_STRING);
        $cell_phone = $request->request->filter('cell-phone', null, FILTER_SANITIZE_STRING);
        $company = $request->request->filter('company', null, FILTER_SANITIZE_STRING);

        $data = [
            'forename' => strtolower(trim($forename)),
            'surname' => strtolower(trim($surname)),
            'email' => strtolower(trim($email)),
            'password' => $password,
            'address' => $address,
            'phone' => $phone,
            'cell_phone' => $cell_phone,
            'company' => $company
        ];

        $repo = $this->storage()->getRepository('customers');

        if (!$repo->emailExists($data['email'])) {

            try {

                $emailverificationcode = $repo->registerCustomer($data);
                if ($emailverificationcode) {
                    
                    // send email to customer

                    $this->session()->getFlashBag()->add('success', 'members:register:registered');
                    return $this->redirectToRoute('memberspage');
                }
            } catch (Exception $ex) {

                return $ex;
            }
        } else {

            $this->session()->getFlashBag()->add('error', 'members:register:emailexists');
            return $this->redirectToRoute('memberspage');
        }

        return $this->redirectToRoute('memberspage');
    }

    public function logout(Request $request) {

        $this->session()->invalidate();
        return $this->redirectToRoute('memberspage');
    }

    public function passrecoverymessage(Request $request) {

        $email = $request->request->filter('email', null, FILTER_SANITIZE_EMAIL);

        $repo = $this->storage()->getRepository('customers');

        if ($repo->emailExists($email)) {

            try {

                $code = $repo->createPasswordRecoveryCode($email);
                if ($code) {

                    //send email to customer

                    $this->session()->getFlashBag()->add('success', 'members:prc:uniquelinksent');
                    return $this->redirectToRoute('memberspage');
                }
            } catch (Exception $ex) {

                return $ex;
            }
        } else {

            $this->session()->getFlashBag()->add('error', 'members:prc:invalidemail');
            return $this->redirectToRoute('memberspage');
        }
    }

    // -------------------------------------------------------------------------

    public function testing(Request $request) {
        
        $response = new JsonResponse([
            'nome' => 'ricardo',
            'apelido' => 'ponce',
            'text' => Trans::__('clique aqui'),
            'locale' => $request->getLocale()
            ]
        );
        
        return $response;

    }

}
