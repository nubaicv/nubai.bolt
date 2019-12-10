<?php

namespace Bundle\Nubai;

use Bolt\Controller\Frontend as NubaiController;
use Bolt\Helpers\Input;
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


        $data_to_template = [
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

        $data_to_template = [
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

            $this->session()->set('customer', $customer[0]);
        } else {

            return $this->redirectToRoute('memberspage', ['from' => 'failedlogin']);
        }

        return $this->redirectToRoute('homepage');
    }

    public function register(Request $request) {

        if ($this->session()->has('customer') === true) {

            $this->session()->invalidate();
            return $this->redirectToRoute('homepage');
        }
        

        if (!$request->request->filter('email', null, FILTER_VALIDATE_EMAIL)) {
            
            //create error message
            return new \Symfony\Component\Config\Definition\Exception\Exception('invalid email');
        }

        $forename = $request->request->filter('forename', null, FILTER_SANITIZE_STRING);
        $surname = $request->request->filter('surname', null, FILTER_SANITIZE_STRING);
        $password = $request->request->filter('password', null, FILTER_SANITIZE_STRING);
        $email = $request->request->filter('email', null, FILTER_SANITIZE_EMAIL);
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
                
                $repo->registerCustomer($data);
            } catch (Exception $ex) {
                
                return $ex;
            }
        } else {
            
            //create error message
            return new \Symfony\Component\Config\Definition\Exception\Exception('email already exists');
        }

        return $this->redirectToRoute('memberspage');
    }

    public function logout(Request $request) {

        $this->session()->invalidate();
        return $this->redirectToRoute('memberspage');
    }

    public function search(Request $request, array $contenttypes = null) {

        // Codigo official do controlador search
        // ---------------------------------------------------------------------------------------------
        $q = '';
        $context = __FUNCTION__;

        if ($request->query->has('q')) {
            $q = $request->query->get('q');
        } elseif ($request->query->has($context)) {
            $q = $request->query->get($context);
        }
        $q = Input::cleanPostedData($q, false);

        $page = $this->app['pager']->getCurrentPage($context);

        $pageSize = $this->getOption('theme/search_results_records', false);
        if ($pageSize === false && !$pageSize = $this->getOption('general/search_results_records', false)) {
            $pageSize = $this->getOption('theme/listing_records', false) ?: $this->getOption('general/listing_records', 10);
        }

        $offset = ($page - 1) * $pageSize;
        $limit = $pageSize;

        // set-up filters from URL
        $filters = [];
        foreach ($request->query->all() as $key => $value) {
            if (strpos($key, '_') > 0) {
                list($contenttypeslug, $field) = explode('_', $key, 2);
                if (isset($filters[$contenttypeslug])) {
                    $filters[$contenttypeslug][$field] = $value;
                } else {
                    $contenttype = $this->getContentType($contenttypeslug);
                    if (is_array($contenttype)) {
                        $filters[$contenttypeslug] = [
                            $field => $value,
                        ];
                    }
                }
            }
        }
        if (count($filters) == 0) {
            $filters = null;
        }

        $isLegacy = $this->getOption('general/compatibility/setcontent_legacy', true);
        if ($isLegacy) {
            $result = $this->storage()->searchContent($q, $contenttypes, $filters, $limit, $offset);

            /** @var \Bolt\Pager\PagerManager $manager */
            $manager = $this->app['pager'];
            $manager
                    ->createPager($context)
                    ->setCount($result['no_of_results'])
                    ->setTotalpages(ceil($result['no_of_results'] / $pageSize))
                    ->setCurrent($page)
                    ->setShowingFrom($offset + 1)
                    ->setShowingTo($offset + ($result ? count($result['results']) : 0));
            ;

            $manager->setLink($this->generateUrl('search', ['q' => $q]) . '&page_search=');
        } else {
            $appCt = array_keys($this->app['query.search_config']->getSearchableTypes());
            $textQuery = '(' . join(',', $appCt) . ')/search';
            $params = [
                'filter' => $q,
                'page' => $page,
                'limit' => $pageSize,
            ];
            $searchResult = $this->getContent($textQuery, $params);

            $result = [
                'results' => $searchResult->getSortedResults(),
                'query' => [
                    'sanitized_q' => strip_tags($q),
                ],
            ];
        }

        $globals = [
            'records' => $result['results'],
            $context => $result['query']['sanitized_q'],
            'searchresult' => $result,
        ];

        $template = $this->templateChooser()->search();

        // ---------------------------------------------------------------------------------------------
        // Meu codigo aqui
        // ---------------------------------------------------------------------------------------------

        $data_to_template = [
        ];

        return $this->render($template, $data_to_template, $globals);
    }

    // -------------------------------------------------------------------------

    public function testing(Request $request) {

        $data = 'Texto para traduzir';

        $data_to_template = [
            'title' => 'My title',
            'data' => $data,
        ];

        return $this->render('testing.twig', $data_to_template);
    }
}
