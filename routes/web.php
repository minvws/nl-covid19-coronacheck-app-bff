<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
|
*/
$router->get('/holder/config', ['middleware' => 'cms_sign', 'uses' => 'HolderController@config']);
$router->get('/holder/config_ctp', ['middleware' => 'cms_sign', 'uses' => 'HolderController@config_ctp']);
$router->get('/holder/public_keys', ['middleware' => 'cms_sign', 'uses' => 'HolderController@public_keys']);
$router->get('/holder/nonce', ['middleware' => 'cms_sign', 'uses' => 'HolderController@nonce']);
$router->post('/holder/get_test_ism', ['middleware' => 'cms_sign', 'uses' => 'HolderController@proof']);

/*
|--------------------------------------------------------------------------
| Monitoring Routes
|--------------------------------------------------------------------------
|
|
*/
$router->get('/status', 'MonitoringController@config');
$router->get('/ping', 'MonitoringController@config');
$router->get('/cache', 'MonitoringController@config');
