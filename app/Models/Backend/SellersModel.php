<?php declare(strict_types = 1); 

namespace App\Models\Backend;

use App\Libraries\Token;
use App\Libraries\Email;

class SellersModel extends BackendModel
{
    protected $table = 'sellers';
    protected $primaryKey = 'id';

    protected $allowedColumns = ['firstname', 'lastname', 'email', 'phone', 'status', 'created_at'];
    protected $allowedFields = ['firstname', 'lastname', 'email', 'phone', 'status', 'images', 'permissions']; 

    protected $selectGetData = 'sellers.id, 
                                (select images.url from images where images.entity_id = sellers.id and images.entity = "sellers" and images.is_cover = "1") as image, 
                                (select count(images.entity_id) from images where images.entity_id = sellers.id and images.entity = "sellers") as galleryOne, 
                                sellers.firstname, 
                                sellers.lastname, 
                                sellers.email, 
                                sellers.phone, 
                                sellers.status, 
                                sellers.master, 
                                sellers.created_at, 
                                sellers.updated_at, 
                                sellers.suspended_at, 
                                sellers.resetted_at';

    protected $selectGetID = 'sellers.id, 
                              sellers.firstname, 
                              sellers.lastname, 
                              sellers.email, 
                              sellers.phone, 
                              sellers.status, 
                              sellers.master, 
                              sellers.created_at, 
                              sellers.updated_at, 
                              sellers.suspended_at, 
                              sellers.resetted_at';

    protected $controller = 'sellers';
    protected $entity = 'sellers';

    public function validationRules()
    {
        return [
            'id' => [
                'rules'  => 'if_exist|alpha_numeric'
            ],
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
                'rules'  => 'required|valid_email|is_unique[sellers.email,id,{id}]'
            ],
            'phone' => [
                'label'  => 'Phone',
                'rules'  => 'required|regex_match[/^([0-9+\s()-]+)$/u]|is_unique[sellers.phone,id,{id}]'
            ],
            'status' => [
                'label'  => 'Status',
                'rules'  => 'required|in_list[0,1]|is_natural'
            ],
            'images' => [
                'label' => 'Images',
                'rules' => 'is_image[images]|max_size[images,' . config('Displaying')->upload_file_size . ']|ext_in[images,jpg,gif,png]|max_dims[images,' . config('Displaying')->upload_file_x . ',' . config('Displaying')->upload_file_y . ']'
            ], 
            'permissions.*' => [
                'label'  => 'Permissions',
                'rules'  => 'if_exist|alpha_dash'
            ]
        ];
    }

    public $searchFields = [ 
        'searchFields.firstname' => [
            'label' => 'Firstname', 
            'rules'  => 'permit_empty|alpha_numeric_space', 
            'errors' => [
                'alpha_numeric_space' => 'Only alphanumeric characters...'
            ]
        ],  
        'searchFields.lastname' => [
            'label' => 'Lastname', 
            'rules'  => 'permit_empty|alpha_numeric_space', 
            'errors' => [
                'alpha_numeric_space' => 'Only alphanumeric characters...'
            ]
        ], 
        'searchFields.email' => [
            'label' => 'Email', 
            'rules'  => 'permit_empty|alpha', 
            'errors' => [
                'alpha' => 'Only alphabetic characters...'
            ]
        ],  
        'searchFields.phone' => [
            'label' => 'Phone', 
            'rules'  => 'permit_empty|numeric', 
            'errors' => [
                'numeric' => 'Only numeric characters...'
            ]
        ]  
    ];

    /* Validation for ajax calls delete, change status */
    public $actionFields = [
        'id' => [
            'rules' => 'required|alpha_numeric'
        ]
    ];

    /* Validation for ajax call change permission */
    public $actionChangePerm = [
        'id' => [
            'rules' => 'required|alpha_numeric'
        ], 
        'permission' => [
            'rules' => 'required|alpha_dash'
        ]
    ];

    public function addAction(Array $posts): Array
    {
        $this->db->transBegin();

            $posts = $this->allowedFields($posts, 'allowedFields');

            $id = $this->uid();

            /* Collecting $_FILES and unsetting the index... */
            $images = $posts['images']; unset($posts['images']);

            /* Collecting $permissions, if they exist, and unsetting the index... */
            if(isset($posts['permissions'])):
                $permissions = [];
                $permissions = $posts['permissions'];
                unset($posts['permissions']);
            endif;

            $posts['id'] = $id;
            $posts['created_at'] = date('Y-m-d H:i:s');

            $this->builder->insert($posts);

            /* Inserting permissions */
            if(isset($permissions)):
                $perms = $this->setPermissionsData($permissions, $id);
                $builder = $this->db->table('sellers_permissions');                
                $builder->insertBatch($perms);
            endif;

            /* Inserting and uploading images */
            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller);
            endif;

            /* Handling activation token */
            $token = new Token();
            $token_hash = $token->getHash();

            $data = [];
            $data['seller_id'] = $id;
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
            return ['result' => false, 'message' => 'Seller inserting failed!'];
        else:
            $this->db->transCommit();
            $seller = $this->getID($id);

                $params = [
                    'to' => esc($seller->email), 
                    'subject' => 'Registration account ' . esc($seller->firstname) . ' ' . esc($seller->lastname), 
                    'firstname' => esc($seller->firstname), 
                    'lastname' => esc($seller->lastname), 
                    'email' => esc($seller->email), 
                    'token' => $token->getValue(), 
                    'action' => 'activation', 
                    'view' => 'setPasswordEmailView.php'
                ];

                if( ! Email::sendEmail($params)):
                    $message = sprintf('Seller <b>%s</b> has been inserted successfully but the activation email was not sent! Contact the administrator for more information!', esc($seller->firstname . ' ' . $seller->lastname));
                else:
                    $message = sprintf('Seller <b>%s</b> has been inserted successfully!', esc($seller->firstname . ' ' . $seller->lastname));
                endif;

            return ['result' => true, 'message' => $message];
        endif;
    }

    public function editAction(Array $posts): Array
    {
        $this->db->transBegin();

            $id = $posts[$this->primaryKey];

            $posts = $this->allowedFields($posts, 'allowedFields');

            // Collecting $_FILES and unsetting the index...
            $images = $posts['images']; unset($posts['images']);

            // Collecting $permissions, if they exist, and unsetting the index...
            if(isset($posts['permissions'])):
                $permissions = [];
                $permissions = $posts['permissions'];
                unset($posts['permissions']);
            endif;

            $posts['updated_at'] = date('Y-m-d H:i:s');

            if(isset($posts['status']) && $posts['status'] === '1'):
                $posts['suspended_at'] = null;
            elseif(isset($posts['status']) && $posts['status'] === '0'):
                $posts['suspended_at'] = date('Y-m-d H:i:s');
            endif;

            $this->builder->update($posts, [$this->primaryKey => $id]);

            if(isset($permissions)):
                $perms = $this->setPermissionsData($permissions, $id);
                $builder = $this->db->table('sellers_permissions');  
                $builder->delete(['seller_id' => $id]);              
                $builder->insertBatch($perms);
            endif;

            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller, 'edit');
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Seller updating failed!'];
        else:
            $this->db->transCommit();
            $seller = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Seller <b>%s</b> has been updated successfully!', esc($seller->firstname . ' ' . $seller->lastname)), 'seller' => $seller];
        endif;
    }

    public function deleteAction(String $id): Array
    {
        $this->db->transBegin();

            /* Before deleting a seller, we check if that seller is present in the orders table, in order to avoid orphan rows */
            if($this->checkRow(['table' => 'orders', 'select' => 'seller_id', 'getwhere' => ['seller_id' => $id]])):
                return ['result' => false, 'message' => 'Non posso cancellare perché relazionata con uno o più orders!'];
            endif;

            $seller = $this->getID($id);

            /* Deleting rows in images table, and collecting the images names to remove physically later the images from the server... */
            $builder = $this->db->table('images');
            $builder->select('url');
            $images = $builder->getWhere(['entity_id' => $id, 'entity' => $this->entity])->getResult();
            $builder->delete(['entity_id' => $id, 'entity' => $this->entity]);

            /* Deleting rows in sellers_permissions */
            $builder = $this->db->table('sellers_permissions');  
            $builder->delete(['seller_id' => $id]);  

            /* Deleting rows in sellers_attempts */
            $builder = $this->db->table('sellers_attempts');  
            $builder->delete(['seller_id' => $id]);  
            
            /* Deleting rows in sellers_tokens */
            $builder = $this->db->table('sellers_tokens');  
            $builder->delete(['seller_id' => $id]); 

            /* Deleting seller */
            $this->builder->delete(['id' => $id]);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> deleting failed!', esc($seller->firstname . ' ' . $seller->lastname))];
        else:
            $this->db->transCommit();
            /* Removing physically the images from the server... */
            $this->removeImages($images);
            return ['result' => true, 'message' => sprintf('Seller <b>%s</b> has been deleted successfully!', esc($seller->firstname . ' ' . $seller->lastname))];
        endif;
    }

    public function changeStatus(String $id): Array
    {
        $this->db->transBegin();

            $seller = $this->getID($id);

            $data = [];

            if($seller->status === '0'):
                $status = '1';
                $data['suspended_at'] = null;
            elseif($seller->status === '1'):
                $status = '0';
                $data['suspended_at'] = date('Y-m-d H:i:s');
            endif;

            $data['status'] = $status;

            $this->builder->where(['id' => $id]);
            $this->builder->update($data);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> changing status failed!', esc($seller->firstname . ' ' . $seller->lastname))];
        else:
            $this->db->transCommit();
            return ['result' => true, 'message' => sprintf('<b>%s</b> seller status has been changed successfully!', esc($seller->firstname . ' ' . $seller->lastname))];
        endif;
    }

    /**
     * This method allows to start the reset password process to a given seller
     */
    public function resetPsw(String $id): Array
    {
        $this->db->transBegin();

            $seller = $this->getID($id);

            /* Updating the data inside the sellers table */
            $data = [];
            $data['resetted_at'] = date('Y-m-d H:i:s');
            $this->builder->where(['id' => $id]);
            $this->builder->update($data);

            /* Handling activation token */
            $token = new Token();
            $token_hash = $token->getHash();

            $data = [];
            $data['seller_id'] = $id;
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
            return ['result' => false, 'message' => sprintf('<b>%s</b> resetting password failed!', esc($seller->firstname . ' ' . $seller->lastname))];
        else:
            $this->db->transCommit();

            $params = [
                'to' => esc($seller->email), 
                'subject' => 'Resetting password ' . esc($seller->firstname) . ' ' . esc($seller->lastname), 
                'firstname' => esc($seller->firstname), 
                'lastname' => esc($seller->lastname), 
                'email' => esc($seller->email), 
                'token' => $token->getValue(), 
                'action' => 'reset', 
                'view' => 'setPasswordEmailView.php'
            ];

            if( ! Email::sendEmail($params)):
                $message = sprintf('<b>%s</b> password has been resetted successfully but the activation email was not sent! Contact the administrator for more information!', esc($seller->firstname . ' ' . $seller->lastname));
            else:
                $message = sprintf('<b>%s</b> password has been resetted successfully!', esc($seller->firstname . ' ' . $seller->lastname));
            endif;

            return ['result' => true, 'message' => $message];
        endif;
    }

    /**
     * This method allows to change one permission on fly to a given seller form the show view
     */
    public function changePermission(String $id, String $permission): Array
    {
        $this->db->transBegin();

            $seller = $this->getID($id);

            $builder = $this->db->table('sellers_permissions');
            $query = $builder->select('*')->limit(1)->getWhere(['seller_id' => $id, 'permission' => $permission]);

            if($query->getNumRows() > 0):
                $builder->delete(['seller_id' => $id, 'permission' => $permission]);
            else:
                $builder->insert(['seller_id' => $id, 'permission' => $permission]);
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> changing permission failed!', esc($seller->firstname . ' ' . $seller->lastname))];
        else:
            $this->db->transCommit();
            return ['result' => true, 'message' => sprintf('<b>%s</b> seller permission has been changed successfully!', esc($seller->firstname . ' ' . $seller->lastname))];
        endif;
    }

    private function setPermissionsData(Array $permissions, String $id): Array
    {
        $perms = [];
        foreach($permissions as $k => $v):
            $perms[$k]['permission'] = $v;
            $perms[$k]['seller_id'] = $id;
        endforeach;
        return $perms;
    }

    /**
     * This method returns all of the permissions assigned to a given seller
     */
    public function getSellerPermissions(String $id): Array
    {
        $builder = $this->db->table('sellers_permissions');
        $permissions = $builder->select('permission')->getWhere(['seller_id' => $id]);

        if($permissions->getNumRows() > 0):

            $perms = [];
            foreach($permissions->getResultArray() as $k => $v):
                $perms[] = $v['permission'];
            endforeach;
            return $perms;

        else:
            return [];
        endif;
    }

}
