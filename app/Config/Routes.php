<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

/*
 * --------------------------------------------------------------------
 * FRONTEND
 * --------------------------------------------------------------------
 */
$routes->get('/', 'Frontend\HomeController::index');

/*
 * --------------------------------------------------------------------
 * BACKEND
 * --------------------------------------------------------------------
 */
$routes->get('admin', function(){
    return redirect('admin/dashboard');
});

$routes->group('admin', function($routes)
{
    $routes->group('auth', function($routes)
    {
        $routes->get('/', 'Backend\AuthController::index', ['filter' => 'sellersnoauthorization']);
        $routes->match(['get', 'post'], 'login', 'Backend\AuthController::login', ['filter' => 'sellersnoauthorization']);
        $routes->match(['get', 'post'], 'recovery', 'Backend\AuthController::recovery', ['filter' => 'sellersnoauthorization']);
        $routes->match(['get', 'post'], 'setPassword/(:alphanum)', 'Backend\AuthController::setPassword/$1', ['filter' => 'sellersnoauthorization']);
        $routes->get('logout', 'Backend\AuthController::logout', ['filter' => 'sellersauthorization']);
    });

    $routes->group('dashboard', function($routes)
    {
        $routes->get('/', 'Backend\DashboardController::index', ['filter' => 'sellersauthorization']);

        $routes->post('basicStats', 'Backend\DashboardController::basicStats', ['filter' => 'sellersauthorization']);
    });

    $routes->group('settings', function($routes)
    {
        $routes->get('/', 'Backend\SettingsController::index', ['filter' => 'sellersauthorizationmaster']);
        $routes->post('getSettings', 'Backend\SettingsController::getSettings', ['filter' => 'sellersauthorizationmaster']);
        $routes->post('saveSettings', 'Backend\SettingsController::saveSettings', ['filter' => 'sellersauthorizationmaster']);
    });

    $routes->group('tools', function($routes)
    {
        $routes->get('/', 'Backend\ToolsController::index', ['filter' => 'sellersauthorizationmaster']);
    });

    $routes->group('brands', function($routes)
    {
        $routes->get('/', 'Backend\BrandsController::index', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'add', 'Backend\BrandsController::add', ['filter' => 'sellersauthorizationpermission:brands,brands_add']); 
        $routes->match(['get', 'post'], 'edit/(:alphanum)', 'Backend\BrandsController::edit/$1', ['filter' => 'sellersauthorizationpermission:brands,brands_edit']); 
        $routes->get('show/(:alphanum)', 'Backend\BrandsController::show/$1', ['filter' => 'sellersauthorizationpermission:brands,brands_show']);
        $routes->delete('delete', 'Backend\BrandsController::delete', ['filter' => 'sellersauthorizationpermission:brands,brands_delete']); 
        $routes->match(['get', 'post'], 'showAll', 'Backend\BrandsController::showAll', ['filter' => 'sellersauthorizationpermission:brands,brands_show_all']); 
    });

    $routes->group('categories', function($routes)
    {
        $routes->get('/', 'Backend\CategoriesController::index', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'add', 'Backend\CategoriesController::add', ['filter' => 'sellersauthorizationpermission:categories,categories_add']);
        $routes->match(['get', 'post'], 'edit/(:alphanum)', 'Backend\CategoriesController::edit/$1', ['filter' => 'sellersauthorizationpermission:categories,categories_edit']); 
        $routes->get('show/(:alphanum)', 'Backend\CategoriesController::show/$1', ['filter' => 'sellersauthorizationpermission:categories,categories_show']);
        $routes->delete('delete', 'Backend\CategoriesController::delete', ['filter' => 'sellersauthorizationpermission:categories,categories_delete']); 
        $routes->match(['get', 'post'], 'showAll', 'Backend\CategoriesController::showAll', ['filter' => 'sellersauthorizationpermission:categories,categories_show_all']); 
    });

    $routes->group('products', function($routes)
    {
        $routes->get('/', 'Backend\ProductsController::index', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'add', 'Backend\ProductsController::add', ['filter' => 'sellersauthorizationpermission:products,products_add']);
        $routes->match(['get', 'post'], 'edit/(:alphanum)', 'Backend\ProductsController::edit/$1', ['filter' => 'sellersauthorizationpermission:products,products_edit']); 
        $routes->get('show/(:alphanum)', 'Backend\ProductsController::show/$1', ['filter' => 'sellersauthorizationpermission:products,products_show']);
        $routes->delete('delete', 'Backend\ProductsController::delete', ['filter' => 'sellersauthorizationpermission:products,products_delete']); 
        $routes->match(['get', 'post'], 'showAll', 'Backend\ProductsController::showAll', ['filter' => 'sellersauthorizationpermission:products,products_show_all']); 

        $routes->post('categoriesDropdown', 'Backend\ProductsController::categoriesDropdown', ['filter' => 'sellersauthorization']);
        $routes->post('checkProductQuantity', 'Backend\ProductsController::checkProductQuantity', ['filter' => 'sellersauthorization']);
        $routes->post('priceCalculateZone', 'Backend\ProductsController::priceCalculateZone', ['filter' => 'sellersauthorization']);
    });

    $routes->group('customers', function($routes)
    {
        $routes->get('/', 'Backend\CustomersController::index', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'add', 'Backend\CustomersController::add', ['filter' => 'sellersauthorizationpermission:customers,customers_add']);
        $routes->match(['get', 'post'], 'edit/(:alphanum)', 'Backend\CustomersController::edit/$1', ['filter' => 'sellersauthorizationpermission:customers,customers_edit']); 
        $routes->get('show/(:alphanum)', 'Backend\CustomersController::show/$1', ['filter' => 'sellersauthorizationpermission:customers,customers_show']);
        $routes->delete('delete', 'Backend\CustomersController::delete', ['filter' => 'sellersauthorizationpermission:customers,customers_delete']); 
        $routes->match(['get', 'post'], 'showAll', 'Backend\CustomersController::showAll', ['filter' => 'sellersauthorizationpermission:customers,customers_show_all']); 
    });

    $routes->group('orders', function($routes)
    {
        $routes->get('/', 'Backend\OrdersController::index', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'add', 'Backend\OrdersController::add', ['filter' => 'sellersauthorizationpermission:orders,orders_add']);
        $routes->match(['get', 'post'], 'edit/(:alphanum)', 'Backend\OrdersController::edit/$1', ['filter' => 'sellersauthorizationpermission:orders,orders_edit']); 
        $routes->get('show/(:alphanum)', 'Backend\OrdersController::show/$1', ['filter' => 'sellersauthorizationpermission:orders,orders_show']);
        $routes->delete('delete', 'Backend\OrdersController::delete', ['filter' => 'sellersauthorizationpermission:orders,orders_delete']); 
        $routes->match(['get', 'post'], 'showAll', 'Backend\OrdersController::showAll', ['filter' => 'sellersauthorizationpermission:orders,orders_show_all']); 

        $routes->post('getProductRow', 'Backend\OrdersController::getProductRow', ['filter' => 'sellersauthorization']);
        $routes->post('categoriesDropdown', 'Backend\OrdersController::categoriesDropdown', ['filter' => 'sellersauthorization']);
        $routes->post('productsDropdown', 'Backend\OrdersController::productsDropdown', ['filter' => 'sellersauthorization']);
        $routes->post('checkOrderQuantity', 'Backend\OrdersController::checkOrderQuantity', ['filter' => 'sellersauthorization']);
    });

    $routes->group('sellers', function($routes)
    {
        $routes->get('/', 'Backend\SellersController::index', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'add', 'Backend\SellersController::add', ['filter' => 'sellersauthorizationmaster']);
        $routes->match(['get', 'post'], 'edit/(:alphanum)', 'Backend\SellersController::edit/$1', ['filter' => 'sellersauthorizationmaster']); 
        $routes->get('show/(:alphanum)', 'Backend\SellersController::show/$1', ['filter' => 'sellersauthorizationmaster']);
        $routes->delete('delete', 'Backend\SellersController::delete', ['filter' => 'sellersauthorizationmaster']); 
        $routes->match(['get', 'post'], 'showAll', 'Backend\SellersController::showAll', ['filter' => 'sellersauthorizationmaster']); 
        $routes->post('changeRole', 'Backend\SellersController::changeRole', ['filter' => 'sellersauthorizationmaster']);
        $routes->post('changeStatus', 'Backend\SellersController::changeStatus', ['filter' => 'sellersauthorizationmaster']);
        $routes->post('resetPsw', 'Backend\SellersController::resetPsw', ['filter' => 'sellersauthorizationmaster']);
        $routes->post('changePermission', 'Backend\SellersController::changePermission', ['filter' => 'sellersauthorizationmaster']);
        $routes->get('tokens', 'Backend\SellersController::tokens', ['filter' => 'sellersauthorizationmaster']);
    });

    $routes->group('account', function($routes)
    {
        $routes->get('/', function(){ return redirect('admin/account/edit'); });
        $routes->match(['get', 'post'], 'edit', 'Backend\AccountController::editAccount', ['filter' => 'sellersauthorization']);
        $routes->get('permissions', 'Backend\AccountController::permissions', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'reset', 'Backend\AccountController::reset', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'images', 'Backend\AccountController::images', ['filter' => 'sellersauthorization']);
        $routes->match(['get', 'post'], 'tokens', 'Backend\AccountController::tokens', ['filter' => 'sellersauthorization']);
    });

    require_once 'Components/GalleryOne/Routes.php';
    require_once 'Components/Tokens/Routes.php';

});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
