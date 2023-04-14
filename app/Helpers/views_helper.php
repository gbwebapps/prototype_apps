<?php 

if ( ! function_exists('menuTopRight'))
{
	/**
	 * Verifica l'esistenza di un cookie
	 */

	function menuTopRight()
	{
		return 
		[
			[
				'controller' => 'sellers', 
				'action' => 'index', 
				'route' => 'admin/sellers/showAll', 
				'icon' => 'fa-solid fa-users', 
				'label' => 'Sellers'
			],
			[
				'controller' => 'account', 
				'action' => 'index', 
				'route' => 'admin/account', 
				'icon' => 'fa-solid fa-user-gear', 
				'label' => 'Account'
			],
			[
				'controller' => 'auth', 
				'action' => 'logout', 
				'route' => 'admin/auth/logout', 
				'icon' => 'fa-solid fa-right-from-bracket', 
				'label' => 'Logout'
			]
		];
	}
}

if ( ! function_exists('menuBottomLeft'))
{
	/**
	 * Verifica l'esistenza di un cookie
	 */

	function menuBottomLeft()
	{
		return 
		[
			[
				'controller' => 'dashboard', 
				'action' => 'index', 
				'route' => 'admin/dashboard', 
				'icon' => 'fa-solid fa-tachometer', 
				'label' => 'Dashboard'
			],
			[
				'controller' => 'categories', 
				'action' => 'index', 
				'route' => 'admin/categories/showAll', 
				'icon' => 'fa-solid fa-diagram-project', 
				'label' => 'Categories'
			],
			[
				'controller' => 'brands', 
				'action' => 'index', 
				'route' => 'admin/brands/showAll', 
				'icon' => 'fa-solid fa-industry', 
				'label' => 'Brands'
			],
			[
				'controller' => 'products', 
				'action' => 'index', 
				'route' => 'admin/products/showAll', 
				'icon' => 'fa-solid fa-gifts', 
				'label' => 'Products'
			],
			[
				'controller' => 'customers', 
				'action' => 'index', 
				'route' => 'admin/customers/showAll', 
				'icon' => 'fa-solid fa-person-walking', 
				'label' => 'Customers'
			],
			[
				'controller' => 'orders', 
				'action' => 'index', 
				'route' => 'admin/orders/showAll', 
				'icon' => 'fa-solid fa-file-signature', 
				'label' => 'Orders'
			]
		];
	}
}

if ( ! function_exists('menuBottomRight'))
{
	/**
	 * Verifica l'esistenza di un cookie
	 */

	function menuBottomRight()
	{
		return [
			[
				'controller' => 'settings', 
				'action' => 'index', 
				'route' => 'admin/settings', 
				'icon' => 'fa-solid fa-cogs', 
				'label' => 'Settings'
			],
			[
				'controller' => 'tools', 
				'action' => 'index', 
				'route' => 'admin/tools', 
				'icon' => 'fa-solid fa-tools', 
				'label' => 'Tools'
			]
		];
	}
}

if ( ! function_exists('showButtons'))
{
	/**
	 * Verifica l'esistenza di un cookie
	 */

	function showButtons($controller, $action)
	{
		$output = '';

		if(in_array($action, ['add', 'edit', 'show'])):

		    $output .= '<div class="mx-auto">';

		        switch($action):

		            case 'add':
		                $id_output = 'addReset';
		                $text_left = 'Reset';
		                $message = 'Are you sure to reset the form?';
		                $text_right = 'Send Data';
		                $btn_left = 'btn-danger btn-sm';
		                $btn_right = 'btn-success btn-sm';
		            break;

		            case 'edit':
		                $id_output = 'editRefresh';
		                $text_left = 'Refresh';
		                $message = 'Are you sure to refresh the form?';
		                $text_right = 'Send Data';
		                $btn_left = 'btn-danger btn-sm';
		                $btn_right = 'btn-success btn-sm';
		            break;

		            case 'show':
		                $text_left = 'Print';
		                $text_right = 'Export PDF';
		                $btn_left = 'btn-danger btn-sm';
		                $btn_right = 'btn-success btn-sm';
		            break;

		        endswitch;

		        $output .= '<button type="button" class="btn ' . $btn_left . ' mx-1"' . (isset($id_output) ? ' id="' . $id_output . '"' : '') . ' data-message="' . (isset($message) ? $message : '') . '">';
		            $output .= $text_left;
		        $output .= '</button>';

		        $output .= '<button type="submit" class="btn ' . $btn_right . ' mx-1" form="' . $controller . '_' . $action . '">';
		            $output .= $text_right;
		        $output .= '</button>';

		    $output .= '</div>';

		endif;

		return $output;
	}
}

if ( ! function_exists('getPermssions'))
{
	/**
	 * Verifica l'esistenza di un cookie
	 */

	function getPermssions()
	{
		return [
			[
			'title' => 'Brands', 
			'controller' => 'brands', 
			'icon' => 'fa-industry', 
			'perms' => [
				'brands_add' => 'Add brand', 
				'brands_edit' => 'Edit brand', 
				'brands_show' => 'Show brand', 
				'brands_delete' => 'Delete brand', 
				'brands_show_all' => 'Show all brands'
				]
			], 
			[
			'title' => 'Categories', 
			'controller' => 'categories', 
			'icon' => 'fa-diagram-project', 
			'perms' => [
				'categories_add' => 'Add category', 
				'categories_edit' => 'Edit category', 
				'categories_show' => 'Show category', 
				'categories_delete' => 'Delete category', 
				'categories_show_all' => 'Show all category'
				]
			], 
			[
			'title' => 'Products', 	
			'controller' => 'products', 
			'icon' => 'fa-gifts', 
			'perms' => [
				'products_add' => 'Add product', 
				'products_edit' => 'Edit product', 
				'products_show' => 'Show product', 
				'products_delete' => 'Delete product', 
				'products_show_all' => 'Show all products'
				]
			], 
			[
			'title' => 'Customers', 
			'controller' => 'customers', 
			'icon' => 'fa-person-walking', 
			'perms' => [
				'customers_add' => 'Add customer', 
				'customers_edit' => 'Edit customer', 
				'customers_show' => 'Show customer', 
				'customers_delete' => 'Delete customer', 
				'customers_show_all' => 'Show all customer'
				]
			], 
			[
			'title' => 'Orders', 
			'controller' => 'orders', 
			'icon' => 'fa-file-signature', 
			'perms' => [
				'orders_add' => 'Add order', 
				'orders_edit' => 'Edit order', 
				'orders_show' => 'Show order', 
				'orders_delete' => 'Delete order', 
				'orders_show_all' => 'Show all order'
				]
			]
		];
	}
}
