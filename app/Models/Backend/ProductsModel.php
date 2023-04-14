<?php

namespace App\Models\Backend;

class ProductsModel extends BackendModel
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $allowedColumns = [
        'brand', 
        'category', 
        'product', 
        'net_price',
        'tax_amount',
        'gross_price',
        'initial_quantity',
        'sold_quantity',
        'available_quantity',
        'unit', 
        'created_at'
    ];

    protected $allowedFields = [
        'brand_id',
        'categoriesProducts',
        'product',
        'description', 
        'net_price',
        'initial_quantity',
        'tax_percentage',
        'unit', 
        'images'
    ]; 

    protected $selectGetData = '
        products.id, 
        (select images.url from images where images.entity_id = products.id and images.entity = "products" and images.is_cover = "1") as image, 
        (select count(images.entity_id) from images where images.entity_id = products.id and images.entity = "products") as galleryOne, 
        brand_id, 
        products.product, 
        net_price, 
        tax_percentage, 
        (select net_price * tax_percentage / 100) as tax_amount, 
        (select net_price + tax_amount) as gross_price, 
        initial_quantity, 
        unit, 
        (select ifnull(initial_quantity - sum(quantity), initial_quantity) from orders_products where product_id = products.id) as available_quantity, 
        (select ifnull(sum(quantity), 0) from orders_products where product_id = products.id) as sold_quantity, 
        products.created_at, 
        products.created_by, 
        (select concat(firstname, " ", lastname) from sellers where sellers.id = products.created_by) as created, 
        products.updated_at, 
        products.updated_by, 
        (select concat(firstname, " ", lastname) from sellers where sellers.id = products.updated_by) as updated, 
        brands.brand
    ';

    protected $joinGetData = [
        'brands' => 'brands.id = products.brand_id'
    ];

    protected $selectGetID = '
        products.id, 
        brand_id, 
        products.product, 
        products.description, 
        net_price, 
        tax_percentage, 
        (select net_price * tax_percentage / 100) as tax_amount, 
        (select net_price + tax_amount) as gross_price, 
        initial_quantity, 
        unit, 
        (select ifnull(initial_quantity - sum(quantity), initial_quantity) from orders_products where product_id = products.id) as available_quantity, 
        (select ifnull(sum(quantity), 0) from orders_products where product_id = products.id) as sold_quantity, 
        products.created_at, 
        products.created_by, 
        (select concat(firstname, " ", lastname) from sellers where sellers.id = products.created_by) as created, 
        products.updated_at, 
        products.updated_by, 
        (select concat(firstname, " ", lastname) from sellers where sellers.id = products.updated_by) as updated, 
        brands.brand
    ';

    protected $joinGetID = [
        'brands' => 'brands.id = products.brand_id'
    ];

    protected $controller = 'products';
    protected $entity = 'products';

    public function validationRules()
    {
        return [
            'id' => [
                'rules'  => 'if_exist|alpha_numeric'
            ],
            'brand_id' => [
                'label' => 'Brand', 
                'rules'  => 'required|alpha_numeric'
            ],
            'categoriesProducts.*' => [
                'label'  => 'Categories',
                'rules'  => 'required|alpha_numeric'
            ],
            'product' => [
                'label'  => 'Product',
                'rules'  => 'required|regex_match[/^([\p{L}0-9\s.,;:!"%&()?+\'\[\]°#\/@-]+)$/u]|is_unique[products.product,id,{id}]'
            ],
            'description' => [
                'label'  => 'Description',
                'rules'  => 'required|regex_match[/^([\p{L}0-9\s.,;:!"%&()?+\'\[\]°#\/@-]+)$/u]'
            ],
            'net_price' => [
                'label'  => 'Net Price',
                'rules'  => 'required|decimal'
            ],
            'initial_quantity' => [
                'label'  => 'Initial Quantity',
                'rules'  => 'required|is_natural'
            ],
            'unit' => [
                'label'  => 'Unit',
                'rules'  => 'required|alpha'
            ],
            'tax_percentage' => [
                'label'  => 'Tax Percentage',
                'rules'  => 'required|decimal'
            ],
            'images' => [
                'label' => 'Images',
                'rules' => 'is_image[images]|max_size[images,' . config('Displaying')->upload_file_size . ']|ext_in[images,jpg,gif,png]|max_dims[images,' . config('Displaying')->upload_file_x . ',' . config('Displaying')->upload_file_y . ']'
            ]
        ];
    }

    public $searchFields = [ 
        'searchFields.product' => [
            'label' => 'Product', 
            'rules'  => 'permit_empty|alpha_numeric', 
            'errors' => [
                'alpha_numeric' => 'Only alphanumeric characters...'
            ]
        ], 
        'searchFields.brand_id' => [
            'label' => 'Brand', 
            'rules'  => 'permit_empty|alpha_numeric', 
            'errors' => [
                'alpha_numeric' => 'Only alphanumeric characters...'
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

            /* Queste sono le categorie selezionate... */
            $categoriesProducts = $posts['categoriesProducts']; unset($posts['categoriesProducts']);

            /* Collecting $_FILES and unsetting the index... */
            $images = $posts['images']; unset($posts['images']);

            $posts['id'] = $id;
            $posts['created_at'] = date('Y-m-d H:i:s');
            $posts['created_by'] = $this->currentSeller->identity->id;

            $this->builder->insert($posts);

            /* Passo il valore delle categorie ricevute alla funzione getCategoriesData che le prepara nel formato voluto da insertBatch */
            $categoriesProducts = $this->getCategoriesData($categoriesProducts, $id); 

            $builder = $this->db->table('categories_products');
            $builder->insertBatch($categoriesProducts);

            /* Inserting and uploading images */
            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller);
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Product inserting failed!'];
        else:
            $this->db->transCommit();
            $product = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Product <b>%s</b> has been inserted successfully!', esc($product->product))];
        endif;
    }

    public function editAction(Array $posts): Array
    {
        $this->db->transBegin();

            $id = (String)$posts['id'];

            $posts = $this->allowedFields($posts, 'allowedFields');

            /* Queste sono le categorie selezionate... */
            $categoriesProducts = $posts['categoriesProducts']; unset($posts['categoriesProducts']);

            /* Collecting $_FILES and unsetting the index... */
            $images = $posts['images']; unset($posts['images']);

            $posts['updated_at'] = date('Y-m-d H:i:s');
            $posts['updated_by'] = $this->currentSeller->identity->id;

            $this->builder->update($posts, [$this->primaryKey => $id]);

            /* Passo il valore delle categorie ricevute alla funzione getCategoriesData che le prepara nel formato voluto da insertBatch */
            $categoriesProducts = $this->getCategoriesData($categoriesProducts, $id);
            
            $builder = $this->db->table('categories_products');
            $builder->delete(['product_id' => $id]);
            $builder->insertBatch($categoriesProducts);

            /* Inserting and uploading images */
            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller, 'edit');
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Product updating failed!'];
        else:
            $this->db->transCommit();
            $product = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Product <b>%s</b> has been updated successfully!', esc($product->product)), 'product' => $product];
        endif;
    }

    public function deleteAction(String $id): Array
    {
        $this->db->transBegin();

            /* Before deleting a product, we check if that product is present in the orders_products table, in order to avoid orphan rows */
            if($this->checkRow(['table' => 'orders_products', 'select' => 'product_id', 'getwhere' => ['product_id' => $id]])):
                return ['result' => false, 'message' => 'Non posso cancellare perché relazionata con uno o più orders!'];
            endif;

            $product = $this->getID($id);

            /* Deleting rows in images table */
            $builder = $this->db->table('images');
            $builder->select('url');
            $images = $builder->getWhere(['entity_id' => $id, 'entity' => $this->controller])->getResult();
            $builder->delete(['entity_id' => $id, 'entity' => $this->controller]);

            /* Deleting product */
            $this->builder->delete(['id' => $id]);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> deleting failed!', esc($product->product))];
        else:
            $this->db->transCommit();
            /* Deleting images */
            $this->removeImages($images);
            return ['result' => true, 'message' => sprintf('Product <b>%s</b> has been deleted successfully!', esc($product->product))];
        endif;
    }

    public function categoriesToProduct(String $id)
    {
        $builder = $this->db->table('categories_products');

        $categoriesToProduct = $builder->select('category_id, category')->join('categories', 'id = category_id')->getWhere(['product_id' => $id])->getResultArray();

        $cats = [];

        foreach ($categoriesToProduct as $cat):
            $cats['ids'][] = $cat['category_id'];
            $cats['categories'][] = $cat['category'];
        endforeach;

        return $cats;
    }

    // Questa funzione prepara dati nel formato voluto dalla funzione insertBatch
    private function getCategoriesData(Array $categoriesProducts, String $id): Array
    {
        $categoriesData = [];

        foreach($categoriesProducts as $k => $v):
            $categoriesData[$k]['product_id'] = $id;
            $categoriesData[$k]['category_id'] = $v;
        endforeach;
        
        return $categoriesData;
    }

    public function checkProductQuantity(String $id, Int $quantity): Array
    {
        $items = $this->warehouse->checkStock($id);

        if($quantity < $items['partial_quantity']):
            $old = $this->getID($id);
            return ['result' => false, 
            'message' => sprintf("New quantity <b>%d</b>, previous quantity <b>%d</b>. The quantity cannot be less than the number of <b>%s</b> already on the order <b>(%d)</b>!", 
            $quantity, 
            $old->initial_quantity, 
            $old->product, 
            $items['partial_quantity'])];
        endif;

        return ['result' => true];
    }

    public function categoriesDropdown(String $id)
    {
        $builder = $this->db->table('brands_categories');

        return $builder->select('category_id, category')->join('categories', 'id = category_id')->getWhere(['brand_id' => $id]);
    }

}
