<?php declare(strict_types = 1); 

namespace App\Models\Backend;

class BrandsModel extends BackendModel
{
    protected $table = 'brands';
    protected $primaryKey = 'id';

    protected $allowedColumns = ['brand', 'created_at'];
    protected $allowedFields = ['brand', 'description', 'images', 'brandsCategories']; 

    protected $selectGetData = 'brands.id, 
                                (select images.url from images where images.entity_id = brands.id and images.entity = "brands" and images.is_cover = "1") as image, 
                                (select count(images.entity_id) from images where images.entity_id = brands.id and images.entity = "brands") as galleryOne, 
                                brands.brand, 
                                brands.created_at, 
                                brands.created_by,
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = brands.created_by) as created, 
                                brands.updated_at, 
                                brands.updated_by, 
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = brands.updated_by) as updated';

    protected $selectGetID = 'brands.id, 
                              brands.brand, 
                              brands.description, 
                              brands.created_at, 
                              brands.created_by,
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = brands.created_by) as created, 
                              brands.updated_at, 
                              brands.updated_by, 
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = brands.updated_by) as updated';

    protected $controller = 'brands';
    protected $entity = 'brands';

    public function validationRules()
    {
        return [
            'id' => [
                'rules'  => 'if_exist|alpha_numeric'
            ],
            'brand' => [
                'label'  => 'Brand',
                'rules'  => 'required|regex_match[/^([\p{L}0-9\s.,;:!"%&()?+\'\[\]°#\/@-]+)$/u]|is_unique[brands.brand,id,{id}]'
            ],
            'brandsCategories.*' => [
                'label'  => 'Categories',
                'rules'  => 'required|alpha_numeric'
            ], 
            'description' => [
                'label'  => 'Description',
                'rules'  => 'required|regex_match[/^([\p{L}0-9\s.,;:!"%&()?+\'\[\]°#\/@-]+)$/u]'
            ],
            'images' => [
                'label' => 'Images',
                'rules' => 'is_image[images]|max_size[images,' . config('Displaying')->upload_file_size . ']|ext_in[images,jpg,gif,png]|max_dims[images,' . config('Displaying')->upload_file_x . ',' . config('Displaying')->upload_file_y . ']'
            ]
        ];
    }

    public $searchFields = [ 
        'searchFields.brand' => [
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
            $brandsCategories = $posts['brandsCategories']; unset($posts['brandsCategories']);

            /* Collecting $_FILES and unsetting the index... */
            $images = $posts['images']; unset($posts['images']);

            $posts['id'] = $id;
            $posts['created_at'] = date('Y-m-d H:i:s');
            $posts['created_by'] = $this->currentSeller->identity->id;

            $this->builder->insert($posts);

            /* Passo il valore delle categorie ricevute alla funzione getCategoriesData che le prepara nel formato voluto da insertBatch */
            $brandsCategories = $this->getCategoriesData($brandsCategories, $id); 

            $builder = $this->db->table('brands_categories');
            $builder->insertBatch($brandsCategories);

            /* Inserting and uploading images */
            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller);
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Brand inserting failed!'];
        else:
            $this->db->transCommit();
            $brand = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Brand <b>%s</b> has been inserted successfully!', esc($brand->brand))];
        endif;
    }

    public function editAction(Array $posts): Array
    {
        $this->db->transBegin();

            $id = $posts[$this->primaryKey];

            $posts = $this->allowedFields($posts, 'allowedFields');

            /* Queste sono le categorie selezionate... */
            $brandsCategories = $posts['brandsCategories']; unset($posts['brandsCategories']);

            /* Collecting $_FILES and unsetting the index... */
            $images = $posts['images']; unset($posts['images']);

            $posts['updated_at'] = date('Y-m-d H:i:s');
            $posts['updated_by'] = $this->currentSeller->identity->id;

            $this->builder->update($posts, [$this->primaryKey => $id]);

            /* Passo il valore delle categorie ricevute alla funzione getCategoriesData che le prepara nel formato voluto da insertBatch */
            $brandsCategories = $this->getCategoriesData($brandsCategories, $id);
            
            $builder = $this->db->table('brands_categories');
            $builder->delete(['brand_id' => $id]);
            $builder->insertBatch($brandsCategories);

            if($filenames = $this->doUpload($images)):
                $this->insertImages($filenames, $id, $this->controller, 'edit');
            endif;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => 'Brand updating failed!'];
        else:
            $this->db->transCommit();
            $brand = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Brand <b>%s</b> has been updated successfully!', esc($brand->brand)), 'brand' => $brand];
        endif;
    }

    public function deleteAction(String $id): Array
    {
        $this->db->transBegin();

            /* Before deleting a brand, we check if that brand is present in the products table, in order to avoid orphan rows */
            if($this->checkRow(['table' => 'products', 'select' => 'brand_id', 'getwhere' => ['brand_id' => $id]])):
                return ['result' => false, 'message' => 'Non posso cancellare perché relazionata con uno o più products!'];
            endif;

            $brand = $this->getID($id);

            /* Deleting rows in images table */
            $builder = $this->db->table('images');
            $builder->select('url');
            $images = $builder->getWhere(['entity_id' => $id, 'entity' => $this->controller])->getResult();
            $builder->delete(['entity_id' => $id, 'entity' => $this->controller]);

            /* Deleting brand */
            $this->builder->delete(['id' => $id]);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> deleting failed!', esc($brand->brand))];
        else:
            $this->db->transCommit();
            /* Deleting images */
            $this->removeImages($images);
            return ['result' => true, 'message' => sprintf('Brand <b>%s</b> has been deleted successfully!', esc($brand->brand))];
        endif;
    }

    // Questa funzione prepara dati nel formato voluto dalla funzione insertBatch
    private function getCategoriesData(Array $brandsCategories, String $id): Array
    {
        $categoriesData = [];

        foreach($brandsCategories as $k => $v):
            $categoriesData[$k]['brand_id'] = $id;
            $categoriesData[$k]['category_id'] = $v;
        endforeach;
        
        return $categoriesData;
    }

    public function categoriesToBrand(String $id)
    {
        $builder = $this->db->table('brands_categories');

        $categoriesToBrand = $builder->select('category_id, category')->join('categories', 'id = category_id')->orderBy('category', 'asc')->getWhere(['brand_id' => $id])->getResultArray();

        $cats = [];

        foreach ($categoriesToBrand as $cat):
            $cats['ids'][] = $cat['category_id'];
            $cats['categories'][] = $cat['category'];
        endforeach;

        return $cats;
    }
}
