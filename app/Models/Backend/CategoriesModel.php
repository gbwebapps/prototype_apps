<?php declare(strict_types = 1); 

namespace App\Models\Backend;

class CategoriesModel extends BackendModel
{
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $allowedColumns = ['category', 'created_at'];
    protected $allowedFields = ['category', 'description', 'images']; 

    protected $selectGetData = 'categories.id, 
                                (select images.url from images where images.entity_id = categories.id and images.entity = "categories" and images.is_cover = "1") as image, 
                                (select count(images.entity_id) from images where images.entity_id = categories.id and images.entity = "categories") as galleryOne, 
                                categories.category, 
                                categories.created_at, 
                                categories.created_by,
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = categories.created_by) as created, 
                                categories.updated_at, 
                                categories.updated_by, 
                                (select concat(firstname, " ", lastname) from sellers where sellers.id = categories.updated_by) as updated';

    protected $selectGetID = 'categories.id, 
                              categories.category, 
                              categories.description, 
                              categories.created_at, 
                              categories.created_by,
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = categories.created_by) as created, 
                              categories.updated_at, 
                              categories.updated_by, 
                              (select concat(firstname, " ", lastname) from sellers where sellers.id = categories.updated_by) as updated';

    protected $controller = 'categories';
    protected $entity = 'categories';

    public function validationRules()
    {
        return [
            'id' => [
                'rules'  => 'if_exist|alpha_numeric'
            ],
            'category' => [
                'label'  => 'Category',
                'rules'  => 'required|regex_match[/^([\p{L}0-9\s.,;:!"%&()?+\'\[\]°#\/@-]+)$/u]|is_unique[categories.category,id,{id}]'
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
        'searchFields.category' => [
            'label' => 'Category', 
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
            return ['result' => false, 'message' => 'Category inserting failed!'];
        else:
            $this->db->transCommit();
            $category = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Category <b>%s</b> has been inserted successfully!', esc($category->category))];
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
            return ['result' => false, 'message' => 'Category updating failed!'];
        else:
            $this->db->transCommit();
            $category = $this->getID($id);
            return ['result' => true, 'message' => sprintf('Category <b>%s</b> has been updated successfully!', esc($category->category)), 'category' => $category];
        endif;
    }

    public function deleteAction(String $id): Array
    {
        $this->db->transBegin();

            /* Before deleting a category, we check if that category is present in the brands_categories table, in order to avoid orphan rows */
            if($this->checkRow(['table' => 'brands_categories', 'select' => 'category_id', 'getwhere' => ['category_id' => $id]])):
                return ['result' => false, 'message' => 'Non posso cancellare perché relazionata con una o più brands!'];
            endif;

            $category = $this->getID($id);

            /* Deleting rows in images table */
            $builder = $this->db->table('images');
            $builder->select('url');
            $images = $builder->getWhere(['entity_id' => $id, 'entity' => $this->controller])->getResult();
            $builder->delete(['entity_id' => $id, 'entity' => $this->controller]);

            /* Deleting category */
            $this->builder->delete(['id' => $id]);

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return ['result' => false, 'message' => sprintf('<b>%s</b> deleting failed!', esc($category->category))];
        else:
            $this->db->transCommit();
            /* Deleting images */
            $this->removeImages($images);
            return ['result' => true, 'message' => sprintf('Category <b>%s</b> has been deleted successfully!', esc($category->category))];
        endif;
    }
}
