<?php 

if ( ! function_exists('dd'))
{
	/**
	 * Funzione di debug per la lettura 
	 * di variabili, oggetti, arrays.
	 */

	function dd($array, Bool $reduced = true)
	{
		$output = ($reduced) ? print_r($array, true) : var_dump($array);

		echo '<pre>' . $output . '</pre>';

		die();
	}
}