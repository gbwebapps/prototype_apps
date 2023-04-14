<?php declare(strict_types = 1); 

namespace App\Models\Backend;

class CustomersModel extends BackendModel
{
    protected $table = 'customers';
    protected $primaryKey = 'id';

    protected $allowedColumns = ['customer', 'tax_code', 'email', 'phone', 'type', 'created_at'];
    protected $allowedFields = ['customer', 'type', 'tax_code', 'email', 'phone', 'images']; 

    protected $selectGetData = 'customers.id, 
                                (select images.url from images where images.entity_id = customers.id and images.entity = "customers" and images.is_cover = "1") as image, 
                                (select count(images.entity_id) from images where images.entity_id = customers.id and images.entity = "customers") as galleryOne, 
                                customers.customer, 
                                customers.tax_code, 
                                customers.email, 
                                customers.phone, 
                                customers.type, 
                                customers.created_at, 
                                customers.created_by,
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = customers.created_by) as created, 
                                customers.updated_at, 
                                customers.updated_by, 
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = customers.updated_by) as updated';

    protected $selectGetID = 'customers.id, 
                              customers.customer, 
                              customers.tax_code, 
                              customers.email, 
                              customers.phone, 
                              customers.type, 
                              customers.created_at, 
                              customers.created_by,
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = customers.created_by) as created, 
                              customers.updated_at, 
                              customers.updated_by, 
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = customers.updated_by) as updated';

    protected $controller = 'customers';
    protected $entity = 'customers';

    public function validationRules()
    {
        return [
            'id' => [
                'rules'  => 'if_exist|alpha_numeric'
            ],
            'customer' => [
                'label'  => 'Customer Name',
                'rules'  => 'required|regex_match[/^([\p{L}0-9\s.,;:!"%&()?+\'\[\]°#\/@-]+)$/u]|is_unique[customers.customer,id,{id}]'
            ],
            'tax_code' => [
                'label'  => 'Code',
                'rules'  => 'required|alpha_numeric|is_unique[customers.tax_code,id,{id}]'
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email|is_unique[customers.email,id,{id}]'
            ],
            'phone' => [
                'label'  => 'Phone',
                'rules'  => 'required|regex_match[/^([0-9+\s()-]+)$/u]|is_unique[customers.phone,id,{id}]'
            ],
            'type' => [
                'label'  => 'Customer Type',
                'rules'  => 'required|in_list[0,1]|is_natural'
            ], 
            'images' => [
                'label' => 'Images',
                'rules' => 'is_image[images]|max_size[images,' . config('Displaying')->upload_file_size . ']|ext_in[images,jpg,gif,png]|max_dims[images,' . config('Displaying')->upload_file_x . ',' . config('Displaying')->upload_file_y . ']'
            ]
        ];
    }

    public $searchFields = [ 
        'searchFields.customer' => [
            'label' => 'Customer', 
            'rules'  => 'permit_empty|alpha_numeric', 
            'errors' => [
                'alpha_numeric' => 'Only alphanumeric characters...'
            ]
        ], 
        'searchFields.tax_code' => [
            'label' => 'Code', 
            'rules'  => 'permit_empty|alpha_numeric', 
            'errors' => [
                'alpha_numeric' => 'Only alphanumeric characters...'
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

    /* Validation for ajax calls delete */
    public $actionFields = [
        'id' => [
            'rules' => 'required|alpha_numeric'
        ]
    ];

    public function addAction(Array $posts): Array
    {
        $this->db->transBegin();

            $id = $this->uid();

            $posts = $this->allowedFields($posts, 'allowedFields');

            // Collecting $_FILES and unsetting the index...
            $images = $posts['images']; unset($posts['images']);

            $posts['id'] = $id;
            $posts['created_at'] = date('Y-m-d H:i:s');
            $posts['created_by'] = $this->currentSeller->identity->id;

            $this->builder->insert($posts);

            /* Inserting and uploading images */
            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller);
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Customer inserting failed!'];
        else:
            $this->db->transCommit();
            $customer = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Customer <b>%s</b> has been inserted successfully!', esc($customer->customer))];
        endif;
    }

    public function editAction(Array $posts): Array
    {
        $this->db->transBegin();

            $id = $posts[$this->primaryKey];

            $posts = $this->allowedFields($posts, 'allowedFields');

            // Collecting $_FILES and unsetting the index...
            $images = $posts['images']; unset($posts['images']);

            $posts['updated_at'] = date('Y-m-d H:i:s');
            $posts['updated_by'] = $this->currentSeller->identity->id;

            $this->builder->update($posts, [$this->primaryKey => $id]);

            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller, 'edit');
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Customer updating failed!'];
        else:
            $this->db->transCommit();
            $customer = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Customer <b>%s</b> has been updated successfully!', esc($customer->customer)), 'customer' => $customer];
        endif;
    }

    public function deleteAction(String $id): Array
    {
        $this->db->transBegin();

            /* Before deleting a customer, we check if that customer is present in the orders table, in order to avoid orphan rows */
            if($this->checkRow(['table' => 'orders', 'select' => 'customer_id', 'getwhere' => ['customer_id' => $id]])):
                return ['result' => false, 'message' => 'Non posso cancellare perché relazionata con uno o più orders!'];
            endif;

            $customer = $this->getID($id);

            /* Deleting rows in images table */
            $builder = $this->db->table('images');
            $builder->select('url');
            $images = $builder->getWhere(['entity_id' => $id, 'entity' => $this->controller])->getResult();
            $builder->delete(['entity_id' => $id, 'entity' => $this->controller]);

            /* Deleting customer */
            $this->builder->delete(['id' => $id]);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> deleting failed!', esc($customer->customer))];
        else:
            $this->db->transCommit();
            /* Deleting images */
            $this->removeImages($images);
            return ['result' => true, 'message' => sprintf('Customer <b>%s</b> has been deleted successfully!', esc($customer->customer))];
        endif;
    }
}
