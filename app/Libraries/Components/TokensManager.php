<?php declare(strict_types = 1);

namespace App\Libraries\Components;

class TokensManager {

	private $db;	
	
	public function __construct()
	{
		$this->db = db_connect();
	}

	public function validateRules()
	{
		return [
			'id' => [
			    'rules' => 'required|is_natural_no_zero'
			]
		];
	}

	/**
	 * Retrieving data for the tokens list.
	 */
	public function show(Array $posts): Array
	{
		/* Initializing the value to count the records */
		$getNumRows = [];

		/* We set the sort order according the value which is coming from the view */
		$posts['order'] = (isset($posts['order']) && $posts['order'] == 'asc') ? 'desc' : 'asc';

		/* We restrict the ordering columns according the relative array */
        $posts['column'] = (isset($posts['column']) && in_array($posts['column'], ['id', 'identity', 'token_create', 'token_expire', 'token_type'])) ? $posts['column'] : 'token_create';

        /* Connecting to the main table */
		$builder = $this->db->table('sellers_tokens');

		/* Selecting the main data */
		$builder->select('sellers_tokens.id, concat(sellers.firstname, " ", sellers.lastname) as identity, sellers_tokens.token_create, sellers_tokens.token_expire, sellers_tokens.token_type, sellers_tokens.ip, sellers_tokens.seller_agent');

		/* Adding the join clauses if present... */
		$builder->join('sellers', 'sellers.id = sellers_tokens.seller_id');

		/* Filtering the data according the search fields if present... */

		/* Sorting the data... */
		$builder->orderBy($posts['column'], $posts['order']);

		/* Limiting the number of the records according the config parameters */
		$builder->limit(config('Displaying')->rows_per_list, ($posts['page'] - 1) * config('Displaying')->rows_per_list);

		/* If there is the seller id, we restrict the list to that seller tokens... */
		if(isset($posts['seller_id'])):
			$builder->where(['seller_id' => $posts['seller_id']]);
			$getNumRows['seller_id'] = $posts['seller_id'];
		endif;

		/* Collecting the data */
		$records = $builder->get();

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
		/* Connecting to the main table */
	    $builder = $this->db->table('sellers_tokens');

	    /* Selecting the data... */
	    $builder->select('sellers_tokens.id, concat(sellers.firstname, " ", sellers.lastname) as identity, sellers_tokens.token_create, sellers_tokens.token_expire, sellers_tokens.token_type, sellers_tokens.ip, sellers_tokens.seller_agent');

	    /* The join clauses... */
	    $builder->join('sellers', 'sellers.id = sellers_tokens.seller_id');

	    /* The search filters... */

	    /* If there is the seller id, we restrict the list to that seller tokens... */
	    if(isset($params['seller_id'])):
	    	$builder->where(['seller_id' => $params['seller_id']]);
	    endif;

	    /* Returning the records number for the pagination data */
	    return $builder->countAllResults();
	}

	/**
	 * Function to delete the tokens...
	 */
	public function delete(String $id): Bool
	{
		/* Connecting to the main table */
	    $builder = $this->db->table('sellers_tokens');

	    /* Deleting data... */
	    if($builder->delete(['id' => $id])):
	    	return true;
	    endif;

	    return false;
	}

}