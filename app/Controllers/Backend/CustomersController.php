<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\CustomersModel;

class CustomersController extends BackendController
{
    private $customersModel;

    public function __construct()
    {
        $this->customersModel = new CustomersModel;

        $this->data['controller'] = 'customers';
        $this->data['entity'] = 'customers';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-chart-simple';
        $this->data['title'] = 'Customers Data';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-list', 'label' => 'Customers List', 'route' => 'admin/customers/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Customer', 'route' => 'admin/customers/add']
        ];

        $this->data['action'] = 'index';
        return view('backend/customers/indexView', $this->data);
    }

    public function add()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'addReset'):

                $output = view('backend/customers/partials/addView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->customersModel->validationRules(); 

                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->customersModel->addAction($posts);
                    $json['token'] = $token;

                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            $this->data['icon'] = 'fa-solid fa-circle-plus';
            $this->data['title'] = 'Add Customer';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Customers Data', 'route' => 'admin/customers'],
                ['icon' => 'fa-solid fa-list', 'label' => 'Customers List', 'route' => 'admin/customers/showAll']
            ];

            $this->data['action'] = 'add';
            return view('backend/customers/addView', $this->data);

        endif;
    }

    public function edit(String $id)
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'editRefresh'):

                /* Check if it is existing */
                if( ! $this->data['customer'] = $this->_getCustomerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $output = view('backend/customers/partials/editView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->customersModel->validationRules(); 
                
                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    /* Check if it is existing */
                    if( ! $this->_getCustomerOr404($id)):
                        $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                        return $this->response->setJSON($json); die();
                    endif;

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->customersModel->editAction($posts);
                    $json['token'] = $token;

                    $this->data['customer'] = $json['customer']; unset($json['customer']);
                    $json['output'] = view('backend/customers/partials/editView', $this->data);
                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            if( ! $this->data['customer'] = $this->_getCustomerOr404($id)):
                return redirect()->to('admin/customers')->with('danger', 'Record non trovato');
            endif;

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';
        
            $this->data['icon'] = 'fa-solid fa-edit';
            $this->data['title'] = 'Edit Customer';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Customers Data', 'route' => 'admin/customers'],
                ['icon' => 'fa-solid fa-list', 'label' => 'Customers List', 'route' => 'admin/customers/showAll'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Customer', 'route' => 'admin/customers/add'], 
                ['icon' => 'fa-solid fa-eye', 'label' => 'Show Customer', 'route' => 'admin/customers/show/' . $id]
            ];

            $this->data['action'] = 'edit';
            return view('backend/customers/editView', $this->data);

        endif;
    }

    public function show(String $id)
    {
        if( ! $this->data['customer'] = $this->_getCustomerOr404($id)):
            return redirect()->to('admin/customers')->with('danger', 'Record non trovato');
        endif;

        $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

        $this->data['icon'] = 'fa-solid fa-eye';
        $this->data['title'] = 'Show Customer';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Customers Data', 'route' => 'admin/customers'],
            ['icon' => 'fa-solid fa-list', 'label' => 'Customers List', 'route' => 'admin/customers/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Customer', 'route' => 'admin/customers/add'], 
            ['icon' => 'fa-solid fa-edit', 'label' => 'Edit Customer', 'route' => 'admin/customers/edit/' . $id]
        ];

        $this->data['action'] = 'show';
        return view('backend/customers/showView', $this->data);
    }

    public function showAll()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $searchFields = ($this->request->getPost('searchFields') == true) ? $this->customersModel->searchFields : [];

            if( ! $this->validate(array_merge($searchFields))):

                $errors = array_replace_key($this->validator->getErrors(), ['searchFields.']);
                $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some search criteria errors...'];
                return $this->response->setJSON($json); die();

            endif;

            $this->data['posts'] = $this->request->getPost();
            $this->data['data'] = $this->customersModel->getData($this->data['posts']);

            $output = view('backend/customers/partials/showAllView', $this->data);
            $json = ['result' => true, 'output' => $output];
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        else:

            $this->data['icon'] = 'fa-solid fa-list';
            $this->data['title'] = 'Customers List';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Customers Data', 'route' => 'admin/customers'],
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Customer', 'route' => 'admin/customers/add'], 
                ['icon' => 'fa-solid fa-search', 'label' => 'Search', 'route' => '#', 'linkAttr' => 'id="linkSearch"'], 
                ['icon' => 'fa-solid fa-sync-alt', 'label' => 'Refresh List', 'route' => '#', 'linkAttr' => 'id="linkRefresh"', 'barItemClass' => ' text-md-right']
            ];

            $this->data['action'] = 'showAll';
            return view('backend/customers/showAllView', $this->data);

        endif;
    }

    public function delete()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->customersModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in deleting...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getCustomerOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->customersModel->deleteAction($id);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        endif;
    }

    private function _getCustomerOr404(String $id)
    {
        $customer = $this->customersModel->getID($id);

        if(is_null($customer)):
            return false;
        else:
            return $customer;
        endif;      
    }
}
