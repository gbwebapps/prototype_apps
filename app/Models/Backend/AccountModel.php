<?php declare(strict_types = 1); 

namespace App\Models\Backend;

use App\Libraries\Token;
use App\Libraries\Email;

class AccountModel extends BackendModel
{
    protected $table = 'sellers';
    protected $primaryKey = 'id';

    protected $allowedColumns = ['id', 'token_create', 'token_expire', 'token_type'];
    protected $allowedFields = ['firstname', 'lastname', 'email', 'phone']; 


    protected $controller = 'account';
    protected $entity = 'sellers';

    /* Rules for the general data form... */
    public function validationRules()
    {
        return [
            'firstname' => [
                'label'  => 'Firstname',
                'rules'  => 'required|regex_match[/^([\p{L}\s\']+)$/u]'
            ],
            'lastname' => [
                'label'  => 'Lastname',
                'rules'  => 'required|regex_match[/^([\p{L}\s\']+)$/u]'
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email|is_unique[sellers.email,id,'.$this->currentSeller->identity->id.']'
            ],
            'phone' => [
                'label'  => 'Phone',
                'rules'  => 'required|regex_match[/^([0-9+\s()-]+)$/u]|is_unique[sellers.phone,id,'.$this->currentSeller->identity->id.']'
            ]
        ];
    }

    /* Rules for the images... */
    public function imagesRules()
    {
        return [
            'images' => [
                'label' => 'Images',
                'rules' => 'uploaded[images]|is_image[images]|max_size[images,' . config('Displaying')->upload_file_size . ']|mime_in[images,image/jpg,image/jpeg,image/gif,image/png]|ext_in[images,jpg,jpeg,gif,png]|max_dims[images,' . config('Displaying')->upload_file_x . ',' . config('Displaying')->upload_file_y . ']'
            ]
        ];
    }

    /**
     * Writing the general data
     */
    public function editAccount(Array $posts): Array
    {
        $this->db->transBegin();

            $posts = $this->allowedFields($posts, 'allowedFields');

            $posts['updated_at'] = date('Y-m-d H:i:s');

            $this->builder->update($posts, [$this->primaryKey => $this->currentSeller->identity->id]);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Account updating failed!'];
        else:
            $this->db->transCommit();
            return ['result' => true, 'message' =>'Your account has been updated successfully!', 'currentSeller' => $this->auth->currentSeller()];
        endif;
    }

    /**
     * Resetting the password
     */
    public function resetPsw(): Array
    {
        $this->db->transBegin();

            /* Updating the data inside the sellers table */
            $data = [];
            $data['resetted_at'] = date('Y-m-d H:i:s');
            $this->builder->where(['id' => $this->currentSeller->identity->id]);
            $this->builder->update($data);

            /* Handling activation token */
            $token = new Token();
            $token_hash = $token->getHash();

            $data = [];
            $data['seller_id'] = $this->currentSeller->identity->id;
            $data['token_hash'] = $token_hash;
            $data['token_create'] = date('Y-m-d H:i:s'); // now
            $data['token_expire'] = date('Y-m-d H:i:s', time() + 43200); // 12 hours
            $data['token_type'] = 'activation';
            $data['seller_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $data['ip'] = $_SERVER['REMOTE_ADDR'];

            $builder = $this->db->table('sellers_tokens');
            $builder->insert($data);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> resetting password failed!', esc($this->currentSeller->identity->firstname . ' ' . $this->currentSeller->identity->lastname))];
        else:
            $this->db->transCommit();

            /* Setting the email params... */
            $params = [
                'to' => esc($this->currentSeller->identity->email), 
                'subject' => 'Resetting password ' . esc($this->currentSeller->identity->firstname) . ' ' . esc($this->currentSeller->identity->lastname), 
                'firstname' => esc($this->currentSeller->identity->firstname), 
                'lastname' => esc($this->currentSeller->identity->lastname), 
                'email' => esc($this->currentSeller->identity->email), 
                'token' => $token->getValue(), 
                'action' => 'reset', 
                'view' => 'setPasswordEmailView.php'
            ];

            /* Sending the email... */
            if( ! Email::sendEmail($params)):
                $message = sprintf('<b>%s</b> password has been resetted successfully but the activation email was not sent! Contact the administrator for more information!', esc($this->currentSeller->identity->firstname . ' ' . $this->currentSeller->identity->lastname));
            else:
                $message = sprintf('<b>%s</b> password has been resetted successfully!', esc($this->currentSeller->identity->firstname . ' ' . $this->currentSeller->identity->lastname));
            endif;

            return ['result' => true, 'message' => $message, 'currentSeller' => $this->auth->currentSeller()];
        endif;
    }

    /**
     * Here we make images upload and write, in the images table, the images names
     */
    public function addImages(Array $images): Array
    {
        /* doUpload() makes the temporaries images moving */
        if($filenames = $this->doUpload($images)): 

            /* insertImages() writes the images names in the images table */
            $this->insertImages($filenames, $this->currentSeller->identity->id, 'sellers', 'edit');
            return ['result' => true, 'message' => 'Images have been uploaded successfully!', 'currentSeller' => $this->auth->currentSeller()];
        endif;

        return ['result' => false, 'message' => 'Images uploading failed!', 'currentSeller' => $this->auth->currentSeller()];
    }
}
