<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\OrdersModel;
use App\Libraries\Dropdowns;
use App\Libraries\Warehouse;

class OrdersController extends BackendController
{
    private $ordersModel;
    private $dropDowns;
    private $warehouse;

    public function __construct()
    {
        $this->ordersModel = new OrdersModel;
        $this->dropDowns = new Dropdowns;
        $this->warehouse = new Warehouse;

        $this->data['controller'] = 'orders';
        $this->data['entity'] = 'orders';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-chart-simple';
        $this->data['title'] = 'Orders Data';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-list', 'label' => 'Orders List', 'route' => 'admin/orders/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Order', 'route' => 'admin/orders/add']
        ];

        $this->data['action'] = 'index';
        return view('backend/orders/indexView', $this->data);
    }

    public function add()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'addReset'):

                $this->data['sellers'] = $this->dropDowns->sellers();
                $this->data['customers'] = $this->dropDowns->customers();

                $output = view('backend/orders/partials/addView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $uniqids = ($this->request->getPost('uniqids') == true) ? $this->request->getPost('uniqids') : false;

                $rules = $this->ordersModel->validationRules($uniqids); 

                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->ordersModel->addAction($posts);
                    $json['token'] = $token;

                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            $this->data['sellers'] = $this->dropDowns->sellers();
            $this->data['customers'] = $this->dropDowns->customers();

            $this->data['icon'] = 'fa-solid fa-circle-plus';
            $this->data['title'] = 'Add Order';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Orders Data', 'route' => 'admin/orders'],
                ['icon' => 'fa-solid fa-list', 'label' => 'Orders List', 'route' => 'admin/orders/showAll']
            ];

            $this->data['action'] = 'add';
            return view('backend/orders/addView', $this->data);

        endif;
    }

    public function edit(String $id)
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'editRefresh'):

                /* Check if it is existing */
                if( ! $this->data['order'] = $this->_getOrderOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $this->data['sellers'] = $this->dropDowns->sellers();
                $this->data['customers'] = $this->dropDowns->customers();
                $this->data['brands'] = $this->dropDowns->brands();

                $this->data['ordersModel'] = $this->ordersModel;
                $this->data['warehouse'] = $this->warehouse;
                
                $this->data['orderProducts'] = $this->ordersModel->getOrderProducts($this->data['order']->id);

                $output = view('backend/orders/partials/editView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $uniqids = ($this->request->getPost('uniqids') == true) ? $this->request->getPost('uniqids') : false;

                $rules = $this->ordersModel->validationRules($uniqids); 
                
                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    /* Check if it is existing */
                    if( ! $this->_getOrderOr404($id)):
                        $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                        return $this->response->setJSON($json); die();
                    endif;

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->ordersModel->editAction($posts);

                    // print_r($json); die();

                    $json['token'] = $token;

                    $this->data['order'] = $json['order']; unset($json['order']);

                    $this->data['sellers'] = $this->dropDowns->sellers();
                    $this->data['customers'] = $this->dropDowns->customers();
                    $this->data['brands'] = $this->dropDowns->brands();

                    $this->data['ordersModel'] = $this->ordersModel;
                    $this->data['warehouse'] = $this->warehouse;
                    
                    $this->data['orderProducts'] = $this->ordersModel->getOrderProducts($this->data['order']->id);

                    $json['output'] = view('backend/orders/partials/editView', $this->data);
                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            if( ! $this->data['order'] = $this->_getOrderOr404($id)):
                return redirect()->to('admin/orders')->with('danger', 'Record non trovato');
            endif;

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

            $this->data['sellers'] = $this->dropDowns->sellers();
            $this->data['customers'] = $this->dropDowns->customers();
            $this->data['brands'] = $this->dropDowns->brands();

            $this->data['ordersModel'] = $this->ordersModel;
            $this->data['warehouse'] = $this->warehouse;

            $this->data['orderProducts'] = $this->ordersModel->getOrderProducts($this->data['order']->id);
        
            $this->data['icon'] = 'fa-solid fa-edit';
            $this->data['title'] = 'Edit Order';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Orders Data', 'route' => 'admin/orders'],
                ['icon' => 'fa-solid fa-list', 'label' => 'Orders List', 'route' => 'admin/orders/showAll'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Order', 'route' => 'admin/orders/add'], 
                ['icon' => 'fa-solid fa-eye', 'label' => 'Show Order', 'route' => 'admin/orders/show/' . $id]
            ];

            $this->data['action'] = 'edit';
            return view('backend/orders/editView', $this->data);

        endif;
    }

    public function show(String $id)
    {
        if( ! $this->data['order'] = $this->_getOrderOr404($id)):
            return redirect()->to('admin/orders')->with('danger', 'Record non trovato');
        endif;

        $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

        $this->data['orderProducts'] = $this->ordersModel->getOrderProducts($this->data['order']->id);

        $this->data['icon'] = 'fa-solid fa-eye';
        $this->data['title'] = 'Show Order';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Orders Data', 'route' => 'admin/orders'],
            ['icon' => 'fa-solid fa-list', 'label' => 'Orders List', 'route' => 'admin/orders/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Order', 'route' => 'admin/orders/add'], 
            ['icon' => 'fa-solid fa-edit', 'label' => 'Edit Order', 'route' => 'admin/orders/edit/' . $id]
        ];

        $this->data['action'] = 'show';
        return view('backend/orders/showView', $this->data);
    }

    public function showAll()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $searchFields = ($this->request->getPost('searchFields') == true) ? $this->ordersModel->searchFields : [];
            $searchDate = ($this->request->getPost('searchDate') == true) ? $this->ordersModel->searchDate : [];

            if( ! $this->validate(array_merge($searchFields, $searchDate))):

                $errors = array_replace_key($this->validator->getErrors(), ['searchFields.', 'searchDate.']);
                $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some search criteria errors...'];
                return $this->response->setJSON($json); die();

            endif;

            $this->data['posts'] = $this->request->getPost();
            $this->data['data'] = $this->ordersModel->getData($this->data['posts']);

            $output = view('backend/orders/partials/showAllView', $this->data);
            $json = ['result' => true, 'output' => $output];
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        else:

            $this->data['sellers'] = $this->dropDowns->sellers();
            $this->data['customers'] = $this->dropDowns->customers();

            $this->data['icon'] = 'fa-solid fa-list';
            $this->data['title'] = 'Orders List';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Orders Data', 'route' => 'admin/orders'],
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Order', 'route' => 'admin/orders/add'], 
                ['icon' => 'fa-solid fa-search', 'label' => 'Search', 'route' => '#', 'linkAttr' => 'id="linkSearch"'], 
                ['icon' => 'fa-solid fa-sync-alt', 'label' => 'Refresh List', 'route' => '#', 'linkAttr' => 'id="linkRefresh"', 'barItemClass' => ' text-md-right']
            ];

            $this->data['action'] = 'showAll';
            return view('backend/orders/showAllView', $this->data);

        endif;
    }

    public function delete()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->ordersModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in deleting...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getOrderOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->ordersModel->deleteAction($id);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        endif;
    }

    public function getProductRow()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();
            
            $this->data['brands'] = $this->dropDowns->brands();
            $this->data['uniqid'] = mt_rand();
            
            $output = view('backend/orders/partials/getProductRow', $this->data);
            $json = ['output' => $output, 'token' => $token];

            return $this->response->setJSON($json); die();

        endif;
    }

    public function categoriesDropdown()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $id = $this->request->getPost('id');

            $this->data['categoriesDropdown'] = $this->ordersModel->categoriesDropdown($id);
            $output = view('backend/orders/partials/categoriesDropdown', $this->data);

            $json = ['output' => $output, 'token' => $token];

            return $this->response->setJSON($json); die();

        endif;
    }

    public function productsDropdown()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();
            
            $category_id = $this->request->getPost('category_id');
            $brand_id = $this->request->getPost('brand_id');

            $this->data['warehouse'] = $this->warehouse;

            $this->data['productsDropdown'] = $this->ordersModel->productsDropdown($category_id, $brand_id);
            $output = view('backend/orders/partials/productsDropdown', $this->data);

            $json = ['output' => $output, 'token' => $token];

            return $this->response->setJSON($json); die();

        endif;
    }

    public function checkOrderQuantity()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $productid = $this->request->getPost('productid');
            $quantity = $this->request->getPost('quantity');
            $orderid = ($this->request->getPost('orderid') == true) ? $this->request->getPost('orderid') : '';

            $json = $this->warehouse->checkOrderQuantity($productid, $quantity, $orderid);
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        endif;
    }

    private function _getOrderOr404(String $id)
    {
        $order = $this->ordersModel->getID($id);

        if(is_null($order)):
            return false;
        else:
            return $order;
        endif;      
    }
}
