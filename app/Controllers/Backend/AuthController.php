<?php declare(strict_types = 1);

namespace App\Controllers\Backend;

use App\Models\Backend\AuthModel;

class AuthController extends BackendController
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = New AuthModel;

        $this->data['controller'] = 'auth';
        $this->data['entity'] = 'sellers';
    }

    public function index()
    {
        $this->data['icon'] = 'fa-solid fa-handshake';
        $this->data['title'] = 'Welcome';

        $this->data['action'] = 'index';
        return view('backend/auth/indexView', $this->data);
    }

    public function login()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->authModel->loginRules;

            if( ! $this->validate($rules)):

                $json = ['errors' => $this->validator->getErrors(), 'token' => $token, 'message' => 'Error validation...'];
                return $this->response->setJSON($json); die();

            else:

                $posts = $this->request->getPost();

                $json = $this->authModel->loginAction($posts);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        else:

            $this->data['beforeStyle']['Auth CSS'] = 'assets/css/backend/auth';

            $this->data['icon'] = 'fa-solid fa-sign-in-alt';
            $this->data['title'] = 'Login';
            
            $this->data['action'] = 'login';
            return view('backend/auth/loginView', $this->data);

        endif;
    }

    public function recovery()
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->authModel->recoveryRules;

            if( ! $this->validate($rules)):

                $json = ['errors' => $this->validator->getErrors(), 'token' => $token, 'message' => 'Error validation...'];
                return $this->response->setJSON($json); die();

            else:

                $posts = $this->request->getPost();

                $json = $this->authModel->recoveryAction($posts);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        else:

            $this->data['beforeStyle']['Auth CSS'] = 'assets/css/backend/auth';

            $this->data['icon'] = 'fa-solid fa-lock';
            $this->data['title'] = 'Recovery';
            
            $this->data['action'] = 'recovery';
            return view('backend/auth/recoveryView', $this->data);

        endif;
    }

    public function setPassword($code)
    {
        if($this->request->isAJAX()):

            $token = csrf_hash();

            $rules = $this->authModel->setPasswordRules;

            if( ! $this->validate($rules)):

                $json = ['errors' => $this->validator->getErrors(), 'token' => $token, 'message' => 'Error validation...'];
                return $this->response->setJSON($json); die();

            else:

                $posts = $this->request->getPost();

                $json = $this->authModel->setPasswordAction($posts);
                $json['token'] = $token;

                return $this->response->setJSON($json); die();

            endif;

        else:
            
            if($this->authModel->checkAuthCode($code)):
                $this->data['beforeStyle']['Auth CSS'] = 'assets/css/backend/auth';
                
                $this->data['code'] = $code;

                $this->data['icon'] = 'fa-solid fa-key';
                $this->data['title'] = 'Set Password';

                $this->data['action'] = 'set_password';
                return view('backend/auth/setPasswordView', $this->data);
            else:
                return redirect()->to(base_url('admin/auth'))->with('danger', 'Il codice di attivazione non va bene o è scaduto!');
            endif;

        endif;
    }

    public function logout()
    {
        $cookie = $this->request->getCookie('remember_me');

        if($cookie):
            $this->authModel->logoutByCookie($cookie);
            return redirect()->to('admin/dashboard')->withCookies();
        else:
            $this->authModel->logoutBySession();
            return redirect()->to('admin/dashboard');
        endif;
    }
}