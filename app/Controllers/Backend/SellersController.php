<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\SellersModel;

class SellersController extends BackendController
{
    private $sellersModel;

    public function __construct()
    {
        $this->sellersModel = new SellersModel;

        $this->data['controller'] = 'sellers';
        $this->data['entity'] = 'sellers';
    }

    /**
     * Displaying the main view. This is useful as a landing page for permission denied messages.
     */
    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-chart-simple';
        $this->data['title'] = 'Sellers Data';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-list', 'label' => 'Sellers List', 'route' => 'admin/sellers/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Seller', 'route' => 'admin/sellers/add']
        ];

        $this->data['action'] = 'index';
        return view('backend/sellers/indexView', $this->data);
    }

    /**
     * This method is assolving 4 tasks.
     * 1) Displaying the view
     * 2) Handling the first ajax call to reset the form
     * 3) Handling the second ajax call to present the form errors
     * 4) Handling the third ajax call to create the record.
     */
    public function add()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            /* Handling the form reset (ajax post) */
            if($this->request->getPost('action') === 'addReset'):

                $output = view('backend/sellers/partials/addView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->sellersModel->validationRules(); 

                /* Handling the form errors (ajax post) */
                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                /* Handling the record creation */
                else:

                    /* Collecting the posts */
                    $posts = $this->request->getPost();

                    /* Collecting the images, if there are some... */
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->sellersModel->addAction($posts);
                    $json['token'] = $token;

                    return $this->response->setJSON($json); die();

                endif;

            endif;

        /* Displaying the view (get) */
        else:
            
            $this->data['icon'] = 'fa-solid fa-circle-plus';
            $this->data['title'] = 'Add Seller';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Sellers Data', 'route' => 'admin/sellers'], 
                ['icon' => 'fa-solid fa-list', 'label' => 'Sellers List', 'route' => 'admin/sellers/showAll'], 
            ];

            $this->data['action'] = 'add';
            return view('backend/sellers/addView', $this->data);

        endif;
    }

    /**
     * This method is assolving 4 tasks.
     * 1) Displaying the view
     * 2) Handling the first ajax call to refresh the form
     * 3) Handling the second ajax call to present the form errors
     * 4) Handling the third ajax call to update the record.
     */
    public function edit(String $id)
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            /* Handling the form refresh (ajax post) */
            if($this->request->getPost('action') === 'editRefresh'):

                /* Check if it is existing */
                if( ! $this->data['seller'] = $this->_getSellerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                /* Collecting the seller permissions to pass to the view */
                $this->data['seller_perms'] = $this->sellersModel->getSellerPermissions($id);

                $output = view('backend/sellers/partials/editView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                /* Check if it is existing */
                if( ! $this->data['seller'] = $this->_getSellerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $rules = $this->sellersModel->validationRules(); 
                
                /* Handling the form errors (ajax post) */
                if( ! $this->validate($rules)):

                    $json = ['errors' => $this->validator->getErrors(), 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                /* Handling the record update */
                else:

                    /* Check if it is existing */
                    if( ! $this->_getSellerOr404($id)):
                        $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                        return $this->response->setJSON($json); die();
                    endif;

                    /* Collecting the posts... */
                    $posts = $this->request->getPost();

                    /* Collecting the images, if there are some... */
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->sellersModel->editAction($posts);
                    $json['token'] = $token;

                    /* Retrieving the updated seller data to pass to the view */
                    $this->data['seller'] = $json['seller']; unset($json['seller']);

                    /* Collecting the seller permissions to pass to the view */
                    $this->data['seller_perms'] = $this->sellersModel->getSellerPermissions($id);

                    $json['output'] = view('backend/sellers/partials/editView', $this->data);
                    return $this->response->setJSON($json); die();

                endif;

            endif;

        /* Displaying the view (get) */
        else:

            /* Check if it is existing */
            if( ! $this->data['seller'] = $this->_getSellerOr404($id)):
                return redirect()->to('admin/sellers')->with('danger', 'Record non trovato');
            endif;

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';
        
            $this->data['icon'] = 'fa-solid fa-edit';
            $this->data['title'] = 'Edit Seller';

            /* Setting the link bar for this view */
            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Sellers Data', 'route' => 'admin/sellers'], 
                ['icon' => 'fa-solid fa-list', 'label' => 'Sellers List', 'route' => 'admin/sellers/showAll'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Seller', 'route' => 'admin/sellers/add'], 
                ['icon' => 'fa-solid fa-eye', 'label' => 'Show Seller', 'route' => 'admin/sellers/show/' . $id]
            ];

            /* Collecting the seller permissions to pass to the view */
            $this->data['seller_perms'] = $this->sellersModel->getSellerPermissions($id);

            $this->data['action'] = 'edit';
            return view('backend/sellers/editView', $this->data);

        endif;
    }

    /* Displaying the show view */
    public function show(String $id)
    {
        /* There might be two types of ajax call here: for the on fly status change */
        if($this->request->isAJAX()):

            $token = csrf_hash();

            /* Check if it is existing */
            if( ! $this->data['seller'] = $this->_getSellerOr404($id)):
                $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                return $this->response->setJSON($json); die();
            endif;

            /* Collecting the seller permissions to pass to the view */
            $this->data['seller_perms'] = $this->sellersModel->getSellerPermissions($id);

            $output = view('backend/sellers/partials/showView', $this->data);
            $json = ['output' => $output, 'token' => $token];

            return $this->response->setJSON($json); die();

        /* Displaying the view (get) */
        else:

            /* Check if it is existing */
            if( ! $this->data['seller'] = $this->_getSellerOr404($id)):
                return redirect()->to('admin/sellers')->with('danger', 'Record non trovato');
            endif;

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';
            $this->data['afterScript']['Tokens Component JS'] = 'assets/js/components/tokens';

            $this->data['icon'] = 'fa-solid fa-eye';
            $this->data['title'] = 'Show Seller';

            /* Setting the link bar for this view */
            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Sellers Data', 'route' => 'admin/sellers'], 
                ['icon' => 'fa-solid fa-list', 'label' => 'Sellers List', 'route' => 'admin/sellers/showAll'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Seller', 'route' => 'admin/sellers/add'], 
                ['icon' => 'fa-solid fa-edit', 'label' => 'Edit Seller', 'route' => 'admin/sellers/edit/' . $id]
            ];

            /* Collecting the seller permissions to pass to the view */
            $this->data['seller_perms'] = $this->sellersModel->getSellerPermissions($id);

            $this->data['action'] = 'show';
            return view('backend/sellers/showView', $this->data);

        endif;
    }

    /**
     * Displaying the showAll view and handling 
     * the ajax call to filter or sorting data
     */
    public function showAll()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $searchFields = ($this->request->getPost('searchFields') == true) ? $this->sellersModel->searchFields : [];

            if( ! $this->validate(array_merge($searchFields))):

                $errors = array_replace_key($this->validator->getErrors(), ['searchFields.']);
                $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some search criteria errors...'];
                return $this->response->setJSON($json); die();

            endif;

            /* Presenting the updated view according the ajax call received, for a page change, for an order sorting change... */

            $this->data['posts'] = $this->request->getPost();
            $this->data['data'] = $this->sellersModel->getData($this->data['posts']);

            $output = view('backend/sellers/partials/showAllView', $this->data);
            $json = ['result' => true, 'output' => $output];
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        /* Displaying the view (get) */
        else:

            $this->data['icon'] = 'fa-solid fa-list';
            $this->data['title'] = 'Sellers List';

            /* Setting the link bar for this view */
            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Sellers Data', 'route' => 'admin/sellers'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Seller', 'route' => 'admin/sellers/add'], 
                ['icon' => 'fa-solid fa-search', 'label' => 'Search', 'route' => '#', 'linkAttr' => 'id="linkSearch"'], 
                ['icon' => 'fa-solid fa-sync-alt', 'label' => 'Refresh List', 'route' => '#', 'linkAttr' => 'id="linkRefresh"', 'barItemClass' => ' text-md-right']
            ];

            $this->data['action'] = 'showAll';
            return view('backend/sellers/showAllView', $this->data);

        endif;
    }

    public function delete() 
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->sellersModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in deleting...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getSellerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->sellersModel->deleteAction($id);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        endif;
    }

    public function changeStatus()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->sellersModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in changing status...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getSellerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->sellersModel->changeStatus($id); 
                $json['token'] = $token;
                return $this->response->setJSON($json); die();

            endif;

        else:
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        endif;
    }

    public function resetPsw()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->sellersModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in resetting password...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getSellerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->sellersModel->resetPsw($id); 
                $json['token'] = $token;
                return $this->response->setJSON($json); die();

            endif;

        else:
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        endif;
    }

    public function changePermission()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->sellersModel->actionChangePerm;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in changing permission...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getSellerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $permission = $this->request->getPost('permission');

                $json = $this->sellersModel->changePermission($id, $permission); 
                $json['token'] = $token;
                return $this->response->setJSON($json); die();

            endif;

        else:
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        endif;
    }

    public function tokens()
    {
        $this->data['afterScript']['Tokens Component JS'] = 'assets/js/components/tokens';
        
        $this->data['icon'] = 'fa-solid fa-list';
        $this->data['title'] = 'Tokens';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-circle-arrow-left', 'label' => 'Sellers List', 'route' => 'admin/sellers/showAll'], 
        ];

        $this->data['action'] = 'tokens';
        return view('backend/sellers/tokensView', $this->data);
    }

    private function _getSellerOr404(String $id)
    {
        $seller = $this->sellersModel->getID($id);

        if(is_null($seller) || $seller->master === '1'):
            return false;
        else:
            return $seller;
        endif;      
    }
}
