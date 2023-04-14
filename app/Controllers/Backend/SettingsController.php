<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\SettingsModel;

class SettingsController extends BackendController
{
    private $sections;
    private $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel;
        
        $this->data['controller'] = 'settings';
        $this->data['entity'] = 'settings';
        
        $this->sections = ['application', 'displaying', 'shield', 'debug'];
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-cogs';
        $this->data['title'] = 'Settings';

        $this->data['sections'] = [
            ['icon' => 'fa-solid fa-chalkboard-teacher', 'name' => 'application'], 
            ['icon' => 'fa-solid fa-tv', 'name' => 'displaying'], 
            ['icon' => 'fa-solid fa-shield-alt', 'name' => 'shield'], 
            ['icon' => 'fa-solid fa-bug', 'name' => 'debug']
        ];

        $this->data['action'] = 'index';
        return view('backend/settings/indexView', $this->data);
    }

    public function getSettings()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $section = $this->request->getPost('section');

            if($this->data['settings'] = $this->settingsModel->getSettings($section)):
                $output = view('backend/settings/partials/' . $section . 'View', $this->data);
                $json = ['result' => true, 'output' => $output];
            else:
                $json = ['result' => false, 'message' => 'Something went wrong in recovering settings...'];
            endif;

            $json['token'] = $token;

            return $this->response->setJSON($json); die();

        else:
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        endif;
    }

    public function saveSettings()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $section = htmlspecialchars($this->request->getPost('section'), ENT_QUOTES);
            if(( ! in_array($section, $this->sections)) || (empty($section))):

                $json = ['result' => false, 'token' => $token, 'message' => 'Section not setted properly...'];
                return $this->response->setJSON($json); die();

            endif;

            $rules = $this->settingsModel->{$section . "Rules"};

            if( ! $this->validate($rules)):

                $json = ['errors' => $this->validator->getErrors(), 'token' => $token, 'message' => 'There are some validation errors...'];
                return $this->response->setJSON($json); die();

            else:

                $posts = $this->request->getPost();

                if($this->data['settings'] = $this->settingsModel->saveSettings($posts)):
                    $output = view('backend/settings/partials/' . $posts['section'] . 'View', $this->data);
                    $json = ['result' => true, 'message' => 'Settings were saved successfully!', 'output' => $output];
                else:
                    $json = ['result' => false, 'message' => 'Something went wrong in saving settings...'];
                endif;

                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        else:
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        endif;
    }
}
