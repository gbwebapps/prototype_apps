<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use App\Models\Backend\AccountModel;

class AccountController extends BackendController
{
    private $accountModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel;

        /* Controller and Entity vars */
        $this->data['controller'] = 'account';
        $this->data['entity'] = 'sellers';

        $this->data['icon'] = 'fa-solid fa-user-gear';
        $this->data['title'] = 'Account';
    }

    /**
     * Displaying the general data view or retrieving the two possible ajax calls...
     */
    public function editAccount()
    {
        /* The ajax request, which is post... */
        if($this->request->isAJAX()):

            $token = csrf_hash();

            /* The refresh button was clicked... */
            if($this->request->getPost('action') === 'refreshAccount'):

                $output = view('backend/account/partials/editView', $this->data);
                $json = ['output' => $output, 'token' => $token];

                return $this->response->setJSON($json); die();

            /* The send data button was clicked... */
            else:

                $rules = $this->accountModel->validationRules(); 

                if( ! $this->validate($rules)):

                    $json = ['errors' => $this->validator->getErrors(), 'token' => $token, 'message' => 'There are some validation errors...'];
                    return $this->response->setJSON($json); die();

                else:

                    $posts = $this->request->getPost();
                    $json = $this->accountModel->editAccount($posts);
                    $json['token'] = $token;

                    /* We retrieve the new current seller data sent here by the editAccount() method in the model, then we unset them... */
                    $this->data['currentSeller'] = $json['currentSeller']; unset($json['currentSeller']);

                    /* We collect the main view */
                    $json['output'] = view('backend/account/partials/editView', $this->data);

                    /* We collect the menu top view and we pass in there the new data, if they are the cover image or the firstname/lastname */
                    $json['menuTop'] = view('backend/template/menuTopView', $this->data);

                    /* Sending to jQuery... */
                    return $this->response->setJSON($json); die();

                endif;

            endif;

        /* the get request, we show the view... */
        else:

            $this->data['action'] = 'edit_account';
            return view('backend/account/editView', $this->data);

        endif;
    }

    /**
     * Displaying the permission view
     */
    public function permissions()
    {
        $this->data['options'] = '';

        $this->data['action'] = 'permissions';
        return view('backend/account/permissionsView', $this->data);
    }

    /**
     * Reset the password. Displaying the view or retrieving the ajax call
     */
    public function reset()
    {
        /* The ajax request, which is with the post method */
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $json = $this->accountModel->resetPsw(); 
            $json['token'] = $token;

            /* We retrieve here the new current seller data because we need the new data about resetting data to put in the view */
            $this->data['currentSeller'] = $json['currentSeller']; unset($json['currentSeller']);

            /* Collecting the main view */
            $json['output'] = view('backend/account/partials/resetView', $this->data);

            /* Sending to jQuery... */
            return $this->response->setJSON($json); die();

        /* The get request, displaying the view */
        else:

            $this->data['action'] = 'reset';
            return view('backend/account/resetView', $this->data);

        endif;
    }

    /**
     * The seller data images management.
     * We might receive the get request and 
     * the ajax request under the post method
     */
    public function images()
    {
        /* The ajax request with post method */
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->accountModel->imagesRules();

            if( ! $this->validate($rules)):

                $json = ['errors' => $this->validator->getErrors(), 'token' => $token, 'message' => 'There are some errors in uploading images...'];
                return $this->response->setJSON($json); die();

            else:

                $images = $this->request->getFiles('images');

                $json = $this->accountModel->addImages($images);
                $json['token'] = $token;

                /* We retrieve the new current seller data sent here by the addImages() method in the model, then we unset them... */
                $this->data['currentSeller'] = $json['currentSeller']; unset($json['currentSeller']);

                /* Collecting the main view */
                $json['output'] = view('backend/account/partials/imagesView', $this->data);

                /* We collect the menu top view and we pass in there the new data, if they are the cover image or the firstname/lastname */
                $json['menuTop'] = view('backend/template/menuTopView', $this->data);

                /* Sending to jQuery... */
                return $this->response->setJSON($json); die();

            endif;

        /* The get request, we show the view */
        else:

            $this->data['afterScript']['Gallery One Component JS'] = 'assets/js/components/galleryOne';

            $this->data['action'] = 'images';
            return view('backend/account/imagesView', $this->data);

        endif;
    }

    /**
     * Displaying the tokens view
     */
    public function tokens()
    {
        $this->data['afterScript']['Tokens Component JS'] = 'assets/js/components/tokens';
        
        $this->data['action'] = 'tokens';
        return view('backend/account/tokensView', $this->data);
    }

}
