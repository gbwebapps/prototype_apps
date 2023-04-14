<?php 

$routes->group('tokens', function($routes)
{
    $routes->post('show', 'Components\TokensController::show');
    $routes->delete('delete', 'Components\TokensController::delete');
});