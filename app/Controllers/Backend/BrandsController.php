<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\BrandsModel;
use App\Libraries\Dropdowns;

class BrandsController extends BackendController
{
    private $brandsModel;
    private $dropDowns;

    public function __construct()
    {
        $this->brandsModel = new BrandsModel;
        $this->dropDowns = new Dropdowns;

        $this->data['controller'] = 'brands';
        $this->data['entity'] = 'brands';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-chart-simple';
        $this->data['title'] = 'Brands Data';

        $this->data['linksBar'] = [ 
            ['icon' => 'fa-solid fa-list', 'label' => 'Brands List', 'route' => 'admin/brands/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Brand', 'route' => 'admin/brands/add']
        ];

        $this->data['action'] = 'index';
        return view('backend/brands/indexView', $this->data);
    }

    public function add()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'addReset'):

                $this->data['categoriesDropdown'] = $this->dropDowns->categories();

                $output = view('backend/brands/partials/addView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->brandsModel->validationRules(); 

                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->brandsModel->addAction($posts);
                    $json['token'] = $token;

                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            $this->data['categoriesDropdown'] = $this->dropDowns->categories();

            $this->data['icon'] = 'fa-solid fa-circle-plus';
            $this->data['title'] = 'Add Brand';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Brands Data', 'route' => 'admin/brands'], 
                ['icon' => 'fa-solid fa-list', 'label' => 'Brands List', 'route' => 'admin/brands/showAll']
            ];

            $this->data['action'] = 'add';
            return view('backend/brands/addView', $this->data);

        endif;
    }

    public function edit(String $id)
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'editRefresh'):

                /* Check if it is existing */
                if( ! $this->data['brand'] = $this->_getBrandOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $this->data['categoriesDropdown'] = $this->dropDowns->categories();
                $this->data['cats'] = $this->brandsModel->categoriesToBrand($id);

                $output = view('backend/brands/partials/editView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->brandsModel->validationRules(); 
                
                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    /* Check if it is existing */
                    if( ! $this->_getBrandOr404($id)):
                        $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                        return $this->response->setJSON($json); die();
                    endif;

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->brandsModel->editAction($posts);
                    $json['token'] = $token;

                    $this->data['categoriesDropdown'] = $this->dropDowns->categories();
                    $this->data['cats'] = $this->brandsModel->categoriesToBrand($id);

                    $this->data['brand'] = $json['brand']; unset($json['brand']);
                    $json['output'] = view('backend/brands/partials/editView', $this->data);
                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            if( ! $this->data['brand'] = $this->_getBrandOr404($id)):
                return redirect()->to('admin/brands')->with('danger', 'Record non trovato');
            endif;

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

            $this->data['categoriesDropdown'] = $this->dropDowns->categories();
            $this->data['cats'] = $this->brandsModel->categoriesToBrand($id);
        
            $this->data['icon'] = 'fa-solid fa-edit';
            $this->data['title'] = 'Edit Brand';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Brands Data', 'route' => 'admin/brands'], 
                ['icon' => 'fa-solid fa-list', 'label' => 'Brands List', 'route' => 'admin/brands/showAll'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Brand', 'route' => 'admin/brands/add'], 
                ['icon' => 'fa-solid fa-eye', 'label' => 'Show Brand', 'route' => 'admin/brands/show/' . $id]
            ];

            $this->data['action'] = 'edit';
            return view('backend/brands/editView', $this->data);

        endif;
    }

    public function show(String $id)
    {
        if( ! $this->data['brand'] = $this->_getBrandOr404($id)):
            return redirect()->to('admin/brands')->with('danger', 'Record non trovato');
        endif;

        $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

        $this->data['cats'] = $this->brandsModel->categoriesToBrand($id);

        $this->data['icon'] = 'fa-solid fa-eye';
        $this->data['title'] = 'Show Brand';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Brands Data', 'route' => 'admin/brands'], 
            ['icon' => 'fa-solid fa-list', 'label' => 'Brands List', 'route' => 'admin/brands/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Brand', 'route' => 'admin/brands/add'], 
            ['icon' => 'fa-solid fa-edit', 'label' => 'Edit Brand', 'route' => 'admin/brands/edit/' . $id]
        ];

        $this->data['action'] = 'show';
        return view('backend/brands/showView', $this->data);
    }

    public function showAll()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $searchFields = ($this->request->getPost('searchFields') == true) ? $this->brandsModel->searchFields : [];

            if( ! $this->validate(array_merge($searchFields))):

                $errors = array_replace_key($this->validator->getErrors(), ['searchFields.']);
                $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some search criteria errors...'];
                return $this->response->setJSON($json); die();

            endif;

            $this->data['posts'] = $this->request->getPost();
            $this->data['data'] = $this->brandsModel->getData($this->data['posts']);

            $this->data['brandsModel'] = $this->brandsModel;

            $output = view('backend/brands/partials/showAllView', $this->data);
            $json = ['result' => true, 'output' => $output];
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        else:

            $this->data['icon'] = 'fa-solid fa-list';
            $this->data['title'] = 'Brands List';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Brands Data', 'route' => 'admin/brands'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Brand', 'route' => 'admin/brands/add'], 
                ['icon' => 'fa-solid fa-search', 'label' => 'Search', 'route' => '#', 'linkAttr' => 'id="linkSearch"'], 
                ['icon' => 'fa-solid fa-sync-alt', 'label' => 'Refresh List', 'route' => '#', 'linkAttr' => 'id="linkRefresh"', 'barItemClass' => ' text-md-right']
            ];

            $this->data['action'] = 'showAll';
            return view('backend/brands/showAllView', $this->data);

        endif;
    }

    public function delete()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->brandsModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in deleting...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getBrandOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->brandsModel->deleteAction($id);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        endif;
    }

    private function _getBrandOr404(String $id)
    {
        $brand = $this->brandsModel->getID($id);

        if(is_null($brand)):
            return false;
        else:
            return $brand;
        endif;      
    }
}
