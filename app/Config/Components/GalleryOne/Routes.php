<?php 

$routes->group('galleryOne', function($routes)
{
    $routes->post('show', 'Components\GalleryOneController::show');
    $routes->post('delete', 'Components\GalleryOneController::delete');
    $routes->post('setCover', 'Components\GalleryOneController::setCover');
    $routes->post('removeCover', 'Components\GalleryOneController::removeCover');
});