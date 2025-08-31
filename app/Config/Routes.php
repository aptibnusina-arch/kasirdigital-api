<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('api', ['namespace' => 'App\Controllers'], static function (RouteCollection $routes): void {
    // Endpoint login
    $routes->post('login', 'AuthController::login');
    $routes->resource('users', ['controller' => 'UserController']);

    $routes->resource('products', ['controller' => 'ProductController']);
    $routes->resource('units', ['controller' => 'UnitController']);
    $routes->resource('sales', ['controller' => 'SaleController']);

    // Preflight untuk login—wajib agar browser tidak blokir
    $routes->options('login', static function () {
        return service('response')
            ->setStatusCode(204)
            ->setHeader('Allow', 'OPTIONS, POST, GET');
    });

    $routes->options('users', static function () {
        return service('response')
            ->setStatusCode(204)
            ->setHeader('Allow', 'OPTIONS, POST, GET');
    });

    $routes->options('products', static function () {
        return service('response')
            ->setStatusCode(204)
            ->setHeader('Allow', 'OPTIONS, POST, GET');
    });

    $routes->options('units', static function () {
        return service('response')
            ->setStatusCode(204)
            ->setHeader('Allow', 'OPTIONS, POST, GET');
    });

    // Contoh rute lain yang mungkin butuh proteksi JWT
    // $routes->get('sales', 'SalesController::index', ['filter' => 'jwt']);
});