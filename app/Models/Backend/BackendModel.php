<?php  declare(strict_types = 1); 

namespace App\Models\Backend;

use App\Libraries\Warehouse;

class BackendModel
{
	protected $db;
	protected $builder;

	protected $table;
	protected $primaryKey;

	protected $allowedColumns;
	protected $allowedFields;
	protected $allowedAccountFields;

	protected $allowedLoginFields;
	protected $allowedRecoveryFields;
	protected $allowedSetPasswordFields;

	protected $selectGetData;
	protected $joinGetData;
	protected $groupByData;

	protected $selectGetID;
	protected $joinGetID;

	protected $controller;
    protected $entity;

    protected $auth;
    protected $currentSeller;

    protected $warehouse;

    public function __construct()
    {
    	$this->db = db_connect();
    	$this->builder = $this->db->table($this->table);

        /* Calling authorization class */
        $this->auth = service('authorization');

        /* Collecting the current seller data */
        $this->currentSeller = $this->auth->currentSeller();

        $this->warehouse = new Warehouse;
    }

    /* Function to create the uid for all of the id columns */
    protected function uid(): String
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes);
    }

    /**
     * Retrieving data for the showAll list.
     * This is used by all of the sections.
     */
    public function getData(Array $posts): Array
    {
        /* Initializing the value to count the records */
    	$getNumRows = [];

        /* We set the sort order according the value which is coming from the view */
        $posts['order'] = (isset($posts['order']) && $posts['order'] == 'asc') ? 'desc' : 'asc';

        /* We restrict the ordering columns according the relative array */
        $posts['column'] = (isset($posts['column']) && in_array($posts['column'], $this->allowedColumns)) ? $posts['column'] : $this->primaryKey;

        /* Selecting the main data */
        $this->builder->select($this->selectGetData);

        /* Adding the join clauses if present... */
        if(isset($this->joinGetData) && ! empty($this->joinGetData)):
            foreach($this->joinGetData as $k => $v):
                $this->builder->join($k, $v, 'left');
            endforeach;
        endif;

        /* Filtering the data according the search fields if present... */
        if((isset($posts['searchFields']))):
            foreach($posts['searchFields'] as $k => $v):
                if( ! empty($v)):
                    $this->builder->like($k, $v);
                endif;
            endforeach;

            /* We count the records according the filters */
            $getNumRows['searchFields'] = $posts['searchFields'];
        endif;

        /* Filtering the data according the search date if present... */
        if((isset($posts['searchDate']))):

            if($this->controller == 'orders'):
                if( ! empty($posts['searchDate']['from'])):
                    $this->builder->where('date > ', $posts['searchDate']['from']);
                endif;

                if( ! empty($posts['searchDate']['to'])):
                    $this->builder->where('date < ', $posts['searchDate']['to']);
                endif;
            endif;

            /* We count the records according the filters */
            $getNumRows['searchFields'] = $posts['searchFields'];
        endif;

        /* Sorting the data... */
        $this->builder->orderBy($posts['column'], $posts['order']);

        /* Limiting the number of the records according the config parameters */
        $this->builder->limit(config('Displaying')->rows_per_list, ($posts['page'] - 1) * config('Displaying')->rows_per_list);

        /* If we are in the sellers section, we avoid to show the master sellers and the current seller */
        if($this->controller === 'sellers'):
            $this->builder->where(['sellers.master != ' => '1']);
        endif;

        if(($this->controller === 'orders') && ($this->currentSeller->identity->master === '0')):
            $this->builder->where(['orders.seller_id = ' => $this->currentSeller->identity->id]);
        endif;

        /* Collecting the data */
        $records = $this->builder->get();

        /* Sending to the getNumRows() function the filters in order to keep the right number of the records... */
        $totalRows = $this->getNumRows($getNumRows);

        /* This is a trick to avoid the blank page when we delete the last record of a page which is not the first one */
        $itemLastPage = $totalRows - (($posts['page'] - 1) * config('Displaying')->rows_per_list); 

        /* Setting the data for the pagination bars */
        $pagination = ['page' => $posts['page'], 'limit' => config('Displaying')->rows_per_list, 'totalRows' => $totalRows];

        /* Returning of the records data, the pagination data, the reference to avoid the problem mentioned above */
        return ['records' => $records, 'pagination' => $pagination, 'itemLastPage' => $itemLastPage];
    }

    /**
     * Function to count the right number of the records according the join clauses and search filters for the right pagination data
     */
    private function getNumRows(Array $params): Int
    {
        /* Selecting the data... */
        $this->builder->select($this->selectGetData);

        /* The join clauses... */
        if(isset($this->joinGetData) && ! empty($this->joinGetData)):
            foreach($this->joinGetData as $k => $v):
                $this->builder->join($k, $v, 'left');
            endforeach;
        endif;

        /* The search filters... */
        if((isset($params['searchFields']))):
            foreach($params['searchFields'] as $k => $v):
                if( ! empty($v)):
                    $this->builder->like($k, $v);
                endif;
            endforeach;
        endif;

        /* The search date filters... */
        if((isset($params['searchDate']))):

            if($this->controller == 'orders'):
                if( ! empty($params['searchDate']['from'])):
                    $this->builder->where('date >= ', $params['searchDate']['from']);
                endif;

                if( ! empty($params['searchDate']['to'])):
                    $this->builder->where('date <= ', $params['searchDate']['to']);
                endif;
            endif;

        endif;

        /* Subtraction of one from the total count if we are in the sellers section... */
        if($this->controller === 'sellers'):
            $this->builder->where(['sellers.master != ' => '1']);
        endif;

        /* Returning the records number for the pagination data */
        return $this->builder->countAllResults();
    }

    /**
     * Retrieving just one row passing the id.
     * This is used by all of the sections.
     */
    public function getId(String $id): Object|Bool
    {
        /* Selecting the data... */
        $this->builder->select($this->selectGetID);

        /* The join clauses if they are present... */
        if(isset($this->joinGetID) && ! empty($this->joinGetID)):
            foreach($this->joinGetID as $k => $v):
                $this->builder->join($k, $v, 'left');
            endforeach;
        endif;

        /* Retrieving the record */
        $query = $this->builder->limit(1)->where([$this->entity . '.' . $this->primaryKey => $id])->get();

        /* If the record is found, we return it... */
        if($query->getNumRows() > 0):
            return $query->getRow();
        endif;

        /* ..else we return false... */
        return false;
    }

    /**
     * Method to check out if the row passed to be deleted is linked with another row 
     * in another table, in order to not cause orphans
     */
    public function checkRow(Array $params): Bool
    {
        $builder = $this->db->table($params['table']);

        $query = $builder->select($params['select'])->getWhere($params['getwhere'])->getRow();

        if(isset($query)):
            return true;
        endif;

        return false;
    }

    /**
     * Method to generate queries on fly from wherever
     */
    public function getQuery(Array $params)
    {
        /* The table to initialize the builder */
        if(isset($params['from']) && ! empty($params['from'])):
            $getQuery = $this->db->table($params['from']);
        else:
            die('It\'s impossible to initialize the builder! Table name is missing!');
        endif;

        /* The SELECT statement */
        if(isset($params['select']) && ! empty($params['select'])):
            $getQuery->select($params['select']);
        endif;

        /* The WHERE statement */
        if(isset($params['where']) && ! empty($params['where'])):
            $getQuery->where($params['where']);
        endif;

        /* The JOIN statement */
        if(isset($params['join']) && ! empty($params['join'])):
            foreach($params['join'] as $join):
                $getQuery->join($join[0], $join[1]);
            endforeach;
        endif;

        /* The LIKE statement */
        if(isset($params['like']) && ! empty($params['like'])):
            $getQuery->like($params['like']);
        endif;

        /* The ORDER BY statement */
        if(isset($params['orderBy']) && ! empty($params['orderBy'])):
            foreach($params['orderBy'] as $orderBy):
                $getQuery->orderBy($orderBy[0], $orderBy[1]);
            endforeach;
        endif;

        /* The LIMIT statement */
        if(isset($params['limit']) && ! empty($params['limit'])):
            $getQuery->limit($params['limit']);
        endif;

        /* The OFFSET statement */
        if(isset($params['offset']) && ! empty($params['offset'])):
            $getQuery->offset($params['offset']);
        endif;

        /* Returning the query */
        return $getQuery->get();
    }

    /* This function removes all of those post data which are not present in the relative array */
    protected function allowedFields(Array $posts, String $rules): Array
    {
        foreach(array_keys($posts) as $key):
            if( ! in_array($key, $this->{$rules}, true)):
                unset($posts[$key]);
            endif;
        endforeach;

        return $posts;
    }

    protected function hasChanged(Array $posts, String $id): Bool
    {

    }

    /**
     * The function to upload the images. The upload allowed is always multiple.
     */
    protected function doUpload(Array $images): Array
	{
        /* Loading the image class to create copies (small and medium) of every image, according the measures established */
        $image = service('image');

        /* Assigning to the measures the relative config params */
        $imagesSizes = ['medium' => [config('Displaying')->resize_medium_x, config('Displaying')->resize_medium_y], 'small' => [config('Displaying')->resize_small_x, config('Displaying')->resize_small_y]];

        /* For security reasons, we check if we are receiving an array... */
        if(is_array($images)):

            $filenames = [];

            foreach($images['images'] as $img):

                if($img->isValid() && ! $img->hasMoved()):

                    $filename = $img->getRandomName();
                    $img->move('images/' . $this->entity . '/large', $filename);

                    /* Here we create the copies (small and medium) of every image */
                    foreach($imagesSizes as $k => $v):
                        $image->withFile('images/' . $this->entity . '/large/' . $filename)
                              ->fit($v[0], $v[1], 'center')
                              ->save('images/' . $this->entity . '/' . $k . '/' . $filename);
                    endforeach;

                    $filenames[] = $filename;

                endif;

            endforeach;

            return $filenames;

        endif;
    }

    /* This function inserts the images data in the images table after having moved the images from the temporary folder to the images folder */
    protected function insertImages(Array $filenames, String $id, String $entity, String $action = 'add'): Void
    {
        $dataImage = [];

        /* Query per sapere se questa entità ha almeno una immagine in is_cover 1 */
        if($action === 'edit'):
            $builder = $this->db->table('images');
            $query = $builder->getwhere(['entity_id' => $id, 'is_cover' => '1', 'entity' => $entity]);
            $flag = ($query->getRow()) ? true : false;
        else:
            $flag = false;
        endif;

        foreach($filenames as $k => $v):
            
            if($flag):
                $dataImage[$k]['is_cover'] = '0';
            else:
                $dataImage[$k]['is_cover'] = (($k === 0) ? '1' : '0');
            endif;

            $dataImage[$k]['entity_id'] = $id;
            $dataImage[$k]['entity'] = $entity;
            $dataImage[$k]['url'] = $v;

        endforeach;

        $builder = $this->db->table('images');
        $builder->insertBatch($dataImage);
    }

    /* This function removes, physically, the images from the server */
    protected function removeImages(Array $images): Void 
    {
        $imagesSizes = ['large', 'medium', 'small'];

        foreach($images as $image):

            foreach($imagesSizes as $size):

                if(file_exists('./images/' . $this->entity . '/' . $size . '/' . $image->url)):
                    unlink('./images/' . $this->entity . '/' . $size . '/' . $image->url);
                endif;

            endforeach;

        endforeach;
    }
}
