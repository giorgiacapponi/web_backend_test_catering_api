<?php

/** @var Bramus\Router\Router $router */

// Define routes here

$router->setBasePath('/web_backend_test_catering_api');
$router->get('/facility', App\Controllers\FacilityController::class . '@read');
$router->post('/facility', App\Controllers\FacilityController::class . '@create');
$router->put('/facility', App\Controllers\FacilityController::class . '@update');
$router->delete('/facility', App\Controllers\FacilityController::class . '@delete');
