<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\ProductsModel;
use App\Libraries\Dropdowns;

class ProductsController extends BackendController
{
    private $productsModel;
    private $dropDowns;

    public function __construct()
    {
        $this->productsModel = new ProductsModel;
        $this->dropDowns = new Dropdowns;

        $this->data['controller'] = 'products';
        $this->data['entity'] = 'products';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-chart-simple';
        $this->data['title'] = 'Products Data';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-list', 'label' => 'Products List', 'route' => 'admin/products/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Product', 'route' => 'admin/products/add']
        ];

        $this->data['action'] = 'index';
        return view('backend/products/indexView', $this->data);
    }

    public function add()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'addReset'):

                $this->data['brandsDropdown'] = $this->dropDowns->brands();

                $output = view('backend/products/partials/addView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->productsModel->validationRules(); 

                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->productsModel->addAction($posts);
                    $json['token'] = $token;

                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            $this->data['brandsDropdown'] = $this->dropDowns->brands();

            $this->data['icon'] = 'fa-solid fa-circle-plus';
            $this->data['title'] = 'Add Product';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Products Data', 'route' => 'admin/products'], 
                ['icon' => 'fa-solid fa-list', 'label' => 'Products List', 'route' => 'admin/products/showAll']
            ];

            $this->data['action'] = 'add';
            return view('backend/products/addView', $this->data);

        endif;
    }

    public function edit(String $id)
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'editRefresh'):

                /* Check if it is existing */
                if( ! $this->data['product'] = $this->_getProductOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $this->data['brandsDropdown'] = $this->dropDowns->brands();
                $this->data['categoriesDropdown'] = $this->productsModel->categoriesDropdown($this->data['product']->brand_id);
                $this->data['cats'] = $this->productsModel->categoriesToProduct($id);

                $output = view('backend/products/partials/editView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->productsModel->validationRules(); 
                
                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    /* Check if it is existing */
                    if( ! $this->_getProductOr404($id)):
                        $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                        return $this->response->setJSON($json); die();
                    endif;

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->productsModel->editAction($posts);
                    $json['token'] = $token;

                    $this->data['brandsDropdown'] = $this->dropDowns->brands();

                    $this->data['product'] = $json['product']; unset($json['product']);

                    $this->data['categoriesDropdown'] = $this->productsModel->categoriesDropdown($this->data['product']->brand_id);
                    $this->data['cats'] = $this->productsModel->categoriesToProduct($id);

                    $json['output'] = view('backend/products/partials/editView', $this->data);
                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            if( ! $this->data['product'] = $this->_getProductOr404($id)):
                return redirect()->to('admin/products')->with('danger', 'Record non trovato');
            endif;

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

            $this->data['brandsDropdown'] = $this->dropDowns->brands();
            $this->data['categoriesDropdown'] = $this->productsModel->categoriesDropdown($this->data['product']->brand_id);
            $this->data['cats'] = $this->productsModel->categoriesToProduct($id);
        
            $this->data['icon'] = 'fa-solid fa-edit';
            $this->data['title'] = 'Edit Product';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Products Data', 'route' => 'admin/products'], 
                ['icon' => 'fa-solid fa-list', 'label' => 'Products List', 'route' => 'admin/products/showAll'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Product', 'route' => 'admin/products/add'], 
                ['icon' => 'fa-solid fa-eye', 'label' => 'Show Product', 'route' => 'admin/products/show/' . $id]
            ];

            $this->data['action'] = 'edit';
            return view('backend/products/editView', $this->data);

        endif;
    }

    public function show(String $id)
    {
        if( ! $this->data['product'] = $this->_getProductOr404($id)):
            return redirect()->to('admin/products')->with('danger', 'Record non trovato');
        endif;

        $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

        $this->data['cats'] = $this->productsModel->categoriesToProduct($id);

        $this->data['icon'] = 'fa-solid fa-eye';
        $this->data['title'] = 'Show Product';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Products Data', 'route' => 'admin/products'], 
            ['icon' => 'fa-solid fa-list', 'label' => 'Products List', 'route' => 'admin/products/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Product', 'route' => 'admin/products/add'], 
            ['icon' => 'fa-solid fa-edit', 'label' => 'Edit Product', 'route' => 'admin/products/edit/' . $id]
        ];

        $this->data['action'] = 'show';
        return view('backend/products/showView', $this->data);
    }

    public function showAll()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $searchFields = ($this->request->getPost('searchFields') == true) ? $this->productsModel->searchFields : [];

            if( ! $this->validate(array_merge($searchFields))):

                $errors = array_replace_key($this->validator->getErrors(), ['searchFields.']);
                $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some search criteria errors...'];
                return $this->response->setJSON($json); die();

            endif;

            $this->data['posts'] = $this->request->getPost();
            $this->data['data'] = $this->productsModel->getData($this->data['posts']);

            $this->data['productsModel'] = $this->productsModel;

            $output = view('backend/products/partials/showAllView', $this->data);
            $json = ['result' => true, 'output' => $output];
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        else:

            $this->data['brandsDropdown'] = $this->dropDowns->brands();

            $this->data['icon'] = 'fa-solid fa-list';
            $this->data['title'] = 'Products List';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Products Data', 'route' => 'admin/products'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Product', 'route' => 'admin/products/add'], 
                ['icon' => 'fa-solid fa-search', 'label' => 'Search', 'route' => '#', 'linkAttr' => 'id="linkSearch"'], 
                ['icon' => 'fa-solid fa-sync-alt', 'label' => 'Refresh List', 'route' => '#', 'linkAttr' => 'id="linkRefresh"', 'barItemClass' => ' text-md-right']
            ];

            $this->data['action'] = 'showAll';
            return view('backend/products/showAllView', $this->data);

        endif;
    }

    public function delete()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->productsModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in deleting...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getProductOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->productsModel->deleteAction($id);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        endif;
    }

    public function categoriesDropdown()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $this->data['id'] = $this->request->getPost('id');
            
            $this->data['categoriesDropdown'] = $this->productsModel->categoriesDropdown($this->data['id']);
            
            $output = view('backend/products/partials/categoriesDropdown', $this->data);
            $json = ['output' => $output, 'token' => $token];

            return $this->response->setJSON($json); die();

        endif;
    }

    public function checkProductQuantity()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $id = $this->request->getPost('id');
            $quantity = (int)$this->request->getPost('quantity');

            $json = $this->productsModel->checkProductQuantity($id, $quantity);
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        endif;
    }

    public function priceCalculateZone()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $this->data['type'] = $this->request->getPost('type');
            $this->data['id'] = ($this->request->getPost('id') == true) ? $this->request->getPost('id') : null;

            if( ! is_null($this->data['id'])):
                $this->data['product'] = $this->_getProductOr404($this->data['id']);
            endif;

            $output = view('backend/products/partials/priceCalculateZone', $this->data);
            $json = ['output' => $output, 'token' => $token];

            return $this->response->setJSON($json); die();

        endif;
    }

    private function _getProductOr404(String $id)
    {
        $product = $this->productsModel->getID($id);

        if(is_null($product)):
            return false;
        else:
            return $product;
        endif;      
    }
}
