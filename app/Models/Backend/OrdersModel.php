<?php declare(strict_types = 1); 

namespace App\Models\Backend;

class OrdersModel extends BackendModel
{
    protected $table = 'orders';
    protected $primaryKey = 'id';

    protected $allowedColumns = ['date', 'customer', 'seller', 'payment', 'created_at', 'orders_net', 'orders_tax', 'orders_total', 'products_number'];
    protected $allowedFields = ['date', 'seller_id', 'customer_id', 'payment', 'images']; 

    protected $selectGetData = 'orders.id, 
                                (select images.url from images where images.entity_id = orders.id and images.entity = "orders" and images.is_cover = "1") as image, 
                                (select count(images.entity_id) from images where images.entity_id = orders.id and images.entity = "orders") as galleryOne, 
                                orders.date, 
                                (select sum(price * quantity) from orders_products where order_id = id) as orders_net, 
                                (select sum((price * quantity) * tax / 100) from orders_products where order_id = id) as orders_tax, 
                                (select sum(orders_net + orders_tax)) as orders_total, 
                                (select count(*) from orders_products where order_id = id) as products_number, 
                                orders.seller_id, 
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = orders.seller_id) as seller, 
                                orders.customer_id, 
                                (select customer from customers where customers.id = orders.customer_id) as customer, 
                                orders.payment, 
                                orders.created_at, 
                                orders.created_by,
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = orders.created_by) as created, 
                                orders.updated_at, 
                                orders.updated_by, 
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = orders.updated_by) as updated';

    protected $selectGetID = 'orders.id, 
                              orders.date, 
                              (select sum(price * quantity) from orders_products where order_id = id) as orders_net, 
                              (select sum((price * quantity) * tax / 100) from orders_products where order_id = id) as orders_tax, 
                              (select sum(orders_net + orders_tax)) as orders_total, 
                              (select count(*) from orders_products where order_id = id) as products_number, 
                              orders.seller_id, 
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = orders.seller_id) as seller, 
                              orders.customer_id, 
                              (select customer from customers where customers.id = orders.customer_id) as customer, 
                              orders.payment, 
                              orders.created_at, 
                              orders.created_by,
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = orders.created_by) as created, 
                              orders.updated_at, 
                              orders.updated_by, 
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = orders.updated_by) as updated';

    protected $controller = 'orders';
    protected $entity = 'orders';

    public function validationRules($uniqids)
    {
        $rules = 
        [
            'id' => [
                'rules'  => 'if_exist|alpha_numeric'
            ],
            'date' => [
                'label'  => 'Date',
                'rules'  => 'required|regex_match[/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/]'
            ],
            'seller_id' => [
                'label'  => 'Seller',
                'rules'  => 'required|alpha_numeric'
            ],
            'customer_id' => [
                'label'  => 'Customer',
                'rules'  => 'required|alpha_numeric'
            ],
            'payment' => [
                'label'  => 'Payment',
                'rules'  => 'required|in_list[cash,credit]|alpha'
            ], 
            'uniqids' => [
                'label'  => 'Uniqids',
                'rules'  => 'required', 
                'errors' => [
                    'required' => 'Products and Quantities are missing...', 
                ]
            ],
            'images' => [
                'label' => 'Images',
                'rules' => 'is_image[images]|max_size[images,' . config('Displaying')->upload_file_size . ']|ext_in[images,jpg,gif,png]|max_dims[images,' . config('Displaying')->upload_file_x . ',' . config('Displaying')->upload_file_y . ']'
            ]
        ];

        if($uniqids):

            foreach($uniqids as $uniqid):

                $rules['product_id_' . $uniqid . '.*'] = [
                    'label'  => 'Product',
                    'rules'  => 'required|alpha_numeric', 
                    'errors' => [
                        'required' => 'Missing...', 
                        'alpha_numeric' => 'Bad format...',
                    ]
                ];

                $rules['quantity_' . $uniqid . '.*'] = [
                    'label'  => 'Quantity',
                    'rules'  => 'required|numeric', 
                    'errors' => [
                        'required' => 'Missing...', 
                        'numeric' => 'Bad format...',
                    ]
                ];

            endforeach;

        endif;

        return $rules;
    }

    public $searchFields = [  
        'searchFields.seller_id' => [
            'label' => 'Seller', 
            'rules'  => 'permit_empty|alpha_numeric', 
            'errors' => [
                'alpha_numeric' => 'Only alphanumeric characters...'
            ]
        ],  
        'searchFields.customer_id' => [
            'label' => 'Customer', 
            'rules'  => 'permit_empty|alpha_numeric', 
            'errors' => [
                'alpha_numeric' => 'Only alphanumeric characters...'
            ]
        ],  
        'searchFields.payment' => [
            'label' => 'Payment', 
            'rules'  => 'permit_empty|in_list[cash,credit]', 
            'errors' => [
                'in_list' => 'Invalid value...'
            ]
        ]
    ];

    public $searchDate = [
        'searchDate.from' => [
            'label' => 'Date From', 
            'rules'  => 'permit_empty|regex_match[/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/]', 
            'errors' => [
                'regex_match' => 'Incorrect date format...'
            ]
        ],  
        'searchDate.to' => [
            'label' => 'Date To', 
            'rules'  => 'permit_empty|regex_match[/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/]', 
            'errors' => [
                'regex_match' => 'Incorrect date format...'
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

            /* Array degli id dei prodotti provenienti dal form */
            $productIds = [];
            foreach($posts['uniqids'] as $uniqid):

                foreach($posts['product_id_' . $uniqid] as $k => $v):
                    $productIds[] = $v;
                endforeach;

            endforeach;

            /* Effettuo la select di tutti i dati dei prodotti scelti nel form */
            $builder = $this->db->table('products');
            $products = $builder->select('id, net_price, product, tax_percentage')->whereIn('id', $productIds)->get();

            /* Verifico se sono stati inseriti duplicati nell'ordine, nel caso blocco l'invio... */
            $data = $this->warehouse->checkDuplicates($productIds, $products);
            if($data):
                return ['result' => false, 'message' => sprintf('You are attempting to insert <b>%d</b> times <b>%s</b> product!', $data['times'], $data['product'])];
            endif;

            /* Array degli id delle quantità provenienti dal form */
            $quantities = [];
            foreach($posts['uniqids'] as $uniqid):

                foreach($posts['quantity_' . $uniqid] as $k => $v):
                    $quantities[] = $v;
                endforeach;

            endforeach;

            $posts = $this->allowedFields($posts, 'allowedFields');

            /**
             * Combino nell'array $attributes i due precedenti arrays (productIds, quantities). Le chiavi sono gli id dei prodotti, i valori le quantità.
             */
            $attributes = array_combine($productIds, $quantities);

            /* Eseguo alcuni ultimi controlli prima dell'inserimento nel database... */
            $data = $this->warehouse->lastCheckAdd($attributes, $products);
            if($data):
                return ['result' => false, 'message' => $data['message']];
            endif;

            // Collecting $_FILES and unsetting the index...
            $images = $posts['images']; unset($posts['images']);

            $posts['id'] = $id;
            $posts['created_at'] = date('Y-m-d H:i:s');
            $posts['created_by'] = $this->currentSeller->identity->id;

            $this->builder->insert($posts);

            /* Connessione alla tabella orders_products... */
            $builder = $this->db->table('orders_products');

            /**
             * Creo l'array secondo il formato voluto da insertBatch 
             * e aggiorno la tabella orders_products
             */
            $insert = $this->warehouse->prepareBatch($attributes, $products, $id);
            $builder->insertBatch($insert);

            /* Inserting and uploading images */
            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller);
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Order inserting failed!'];
        else:
            $this->db->transCommit();
            $order = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Order <b>%s</b> has been inserted successfully!', esc($order->date))];
        endif;
    }

    public function editAction(Array $posts): Array
    {
        $this->db->transBegin();

            $id = $posts[$this->primaryKey];

            $order = $this->getID($id);

            /* Array degli id dei prodotti provenienti dal form */
            $productIds = [];
            foreach($posts['uniqids'] as $uniqid):

                foreach($posts['product_id_' . $uniqid] as $k => $v):
                    $productIds[] = $v;
                endforeach;

            endforeach;

            /* Effettuo la select di tutti i dati dei prodotti scelti nel form */
            $builder = $this->db->table('products');
            $products = $builder->select('id, net_price, product, tax_percentage')->whereIn('id', $productIds)->get();

            /* Verifico se sono stati inseriti duplicati nell'ordine, nel caso blocco l'invio... */
            $data = $this->warehouse->checkDuplicates($productIds, $products);
            if($data):
                return ['result' => false, 'message' => sprintf('You are attempting to insert <b>%d</b> times <b>%s</b> product!', $data['times'], $data['product']), 'order' => $order];
            endif;

            /* Array degli id delle quantità provenienti dal form */
            $quantities = [];
            foreach($posts['uniqids'] as $uniqid):

                foreach($posts['quantity_' . $uniqid] as $k => $v):
                    $quantities[] = $v;
                endforeach;

            endforeach;

            $posts = $this->allowedFields($posts, 'allowedFields');

            /**
             * Combino nell'array $attributes i due precedenti arrays (productIds, quantities). Le chiavi sono gli id dei prodotti, i valori le quantità.
             */
            $attributes = array_combine($productIds, $quantities);

            /* Eseguo alcuni ultimi controlli prima dell'inserimento nel database... */
            $data = $this->warehouse->lastCheckEdit($attributes, $products);
            if($data):
                return ['result' => false, 'message' => $data['message'], 'order' => $order];
            endif;

            // Collecting $_FILES and unsetting the index...
            $images = $posts['images']; unset($posts['images']);

            $posts['updated_at'] = date('Y-m-d H:i:s');
            $posts['updated_by'] = $this->currentSeller->identity->id;

            $this->builder->update($posts, [$this->primaryKey => $id]);

            /* Deleting order references from orders_products */
            $builder = $this->db->table('orders_products');
            $builder->delete(['order_id' => $id]);

            /**
             * Creo l'array secondo il formato voluto da insertBatch 
             * e aggiorno la tabella orders_products
             */
            $insert = $this->warehouse->prepareBatch($attributes, $products, $id);
            $builder->insertBatch($insert);

            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller, 'edit');
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Order updating failed!', 'order' => $order];
        else:
            $this->db->transCommit();
            $order = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Order made on <b>%s</b> has been updated successfully!', esc($order->date)), 'order' => $order];
        endif;
    }

    public function deleteAction(String $id): Array
    {
        $this->db->transBegin();

            $order = $this->getID($id);

            /* Deleting rows in images table */
            $builder = $this->db->table('images');
            $builder->select('url');
            $images = $builder->getWhere(['entity_id' => $id, 'entity' => $this->controller])->getResult();
            $builder->delete(['entity_id' => $id, 'entity' => $this->controller]);

            /* Deleting order references from orders_products */
            $builder = $this->db->table('orders_products');
            $builder->delete(['order_id' => $id]);

            /* Deleting order */
            $this->builder->delete(['id' => $id]);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('Deleting order made on <b>%s</b> failed!', esc($order->date))];
        else:
            $this->db->transCommit();
            /* Deleting images */
            $this->removeImages($images);
            return ['result' => true, 'message' => sprintf('Order made on <b>%s</b> has been deleted successfully!', esc($order->date))];
        endif;
    }

    public function getOrderProducts(String $id): Object
    {
        $builder = $this->db->table('products');

        $data = $builder->select('products.id, products.brand_id, brands.brand, products.product, products.net_price, products.tax_percentage, orders_products.quantity, categories.id as category_id, categories.category')
                        ->join('brands', 'brands.id = products.brand_id')
                        ->join('orders_products', 'orders_products.product_id = products.id')
                        ->join('categories_products', 'categories_products.product_id = products.id')
                        ->join('categories', 'categories.id = categories_products.category_id')
                        ->getWhere(['orders_products.order_id' => $id]);

        return $data;
    }

    public function categoriesDropdown(String $id)
    {
        $builder = $this->db->table('brands_categories');

        return $builder->select('category_id, category')->join('categories', 'id = category_id')->getWhere(['brand_id' => $id]);
    }

    public function productsDropdown(String $category_id, String $brand_id)
    {
        $builder = $this->db->table('categories_products');

        return $builder->select('product_id, product, net_price, tax_percentage')->join('products', 'id = product_id')->getWhere(['brand_id' => $brand_id, 'category_id' => $category_id]);
    }
    
}
