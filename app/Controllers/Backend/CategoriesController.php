<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\CategoriesModel;

class CategoriesController extends BackendController
{
    private $categoriesModel;

    public function __construct()
    {
        $this->categoriesModel = new CategoriesModel;

        $this->data['controller'] = 'categories';
        $this->data['entity'] = 'categories';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-chart-simple';
        $this->data['title'] = 'Categories Data';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-list', 'label' => 'Categories List', 'route' => 'admin/categories/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Category', 'route' => 'admin/categories/add']
        ];

        $this->data['action'] = 'index';
        return view('backend/categories/indexView', $this->data);
    }

    public function add()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'addReset'):

                $output = view('backend/categories/partials/addView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->categoriesModel->validationRules(); 

                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->categoriesModel->addAction($posts);
                    $json['token'] = $token;

                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            $this->data['icon'] = 'fa-solid fa-circle-plus';
            $this->data['title'] = 'Add Category';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Categories Data', 'route' => 'admin/categories'],
                ['icon' => 'fa-solid fa-list', 'label' => 'Categories List', 'route' => 'admin/categories/showAll']
            ];

            $this->data['action'] = 'add';
            return view('backend/categories/addView', $this->data);

        endif;
    }

    public function edit(String $id)
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            if($this->request->getPost('action') === 'editRefresh'):

                /* Check if it is existing */
                if( ! $this->data['category'] = $this->_getCategoryOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $output = view('backend/categories/partials/editView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            else:

                $rules = $this->categoriesModel->validationRules(); 
                
                if( ! $this->validate($rules)):

                    $errors = array_replace_key($this->validator->getErrors());
                    $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    /* Check if it is existing */
                    if( ! $this->_getCategoryOr404($id)):
                        $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                        return $this->response->setJSON($json); die();
                    endif;

                    $posts = $this->request->getPost();
                    $posts['images'] = $this->request->getFiles('images');

                    $json = $this->categoriesModel->editAction($posts);
                    $json['token'] = $token;

                    $this->data['category'] = $json['category']; unset($json['category']);
                    $json['output'] = view('backend/categories/partials/editView', $this->data);
                    return $this->response->setJSON($json); die();

                endif;

            endif;

        else:

            if( ! $this->data['category'] = $this->_getCategoryOr404($id)):
                return redirect()->to('admin/categories')->with('danger', 'Record non trovato');
            endif;

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';
        
            $this->data['icon'] = 'fa-solid fa-edit';
            $this->data['title'] = 'Edit Category';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Categories Data', 'route' => 'admin/categories'],
                ['icon' => 'fa-solid fa-list', 'label' => 'Categories List', 'route' => 'admin/categories/showAll'], 
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Category', 'route' => 'admin/categories/add'], 
                ['icon' => 'fa-solid fa-eye', 'label' => 'Show Category', 'route' => 'admin/categories/show/' . $id]
            ];

            $this->data['action'] = 'edit';
            return view('backend/categories/editView', $this->data);

        endif;
    }

    public function show(String $id)
    {
        if( ! $this->data['category'] = $this->_getCategoryOr404($id)):
            return redirect()->to('admin/categories')->with('danger', 'Record non trovato');
        endif;

        $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

        $this->data['icon'] = 'fa-solid fa-eye';
        $this->data['title'] = 'Show Category';

        $this->data['linksBar'] = [
            ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Categories Data', 'route' => 'admin/categories'],
            ['icon' => 'fa-solid fa-list', 'label' => 'Categories List', 'route' => 'admin/categories/showAll'], 
            ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Category', 'route' => 'admin/categories/add'], 
            ['icon' => 'fa-solid fa-edit', 'label' => 'Edit Category', 'route' => 'admin/categories/edit/' . $id]
        ];

        $this->data['action'] = 'show';
        return view('backend/categories/showView', $this->data);
    }

    public function showAll()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $searchFields = ($this->request->getPost('searchFields') == true) ? $this->categoriesModel->searchFields : [];

            if( ! $this->validate(array_merge($searchFields))):

                $errors = array_replace_key($this->validator->getErrors(), ['searchFields.']);
                $json = ['errors' => $errors, 'token' => $token, 'message' => 'There are some search criteria errors...'];
                return $this->response->setJSON($json); die();

            endif;

            $this->data['posts'] = $this->request->getPost();
            $this->data['data'] = $this->categoriesModel->getData($this->data['posts']);

            $output = view('backend/categories/partials/showAllView', $this->data);
            $json = ['result' => true, 'output' => $output];
            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        else:

            $this->data['icon'] = 'fa-solid fa-list';
            $this->data['title'] = 'Categories List';

            $this->data['linksBar'] = [
                ['icon' => 'fa-solid fa-chart-simple', 'label' => 'Categories Data', 'route' => 'admin/categories'],
                ['icon' => 'fa-solid fa-circle-plus', 'label' => 'Add Category', 'route' => 'admin/categories/add'], 
                ['icon' => 'fa-solid fa-search', 'label' => 'Search', 'route' => '#', 'linkAttr' => 'id="linkSearch"'], 
                ['icon' => 'fa-solid fa-sync-alt', 'label' => 'Refresh List', 'route' => '#', 'linkAttr' => 'id="linkRefresh"', 'barItemClass' => ' text-md-right']
            ];

            $this->data['action'] = 'showAll';
            return view('backend/categories/showAllView', $this->data);

        endif;
    }

    public function delete()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->categoriesModel->actionFields;

            if( ! $this->validate($rules)):

                $json = ['errors' => true, 'token' => $token, 'message' => 'There are some errors in deleting...'];
                return $this->response->setJSON($json); die();

            else:

                $id = $this->request->getPost('id');

                /* Check if it is existing */
                if( ! $this->_getCategoryOr404($id)):
                    $json = ['result' => false, 'token' => $token, 'message' => 'Record non trovato!'];
                    return $this->response->setJSON($json); die();
                endif;

                $json = $this->categoriesModel->deleteAction($id);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        endif;
    }

    private function _getCategoryOr404(String $id)
    {
        $category = $this->categoriesModel->getID($id);

        if(is_null($category)):
            return false;
        else:
            return $category;
        endif;      
    }
}
