<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
|
*/
$router->get(
    '/v1/holder/config',
    [
        'middleware' => ['cms_sign','cdn_json:holder_config.json'],
        'uses' => 'HolderController@cdnjson'
    ]
);

$router->get('/v1/holder/config_ctp', ['middleware' => ['cms_sign','cdn_json:holder_config_ctp.json'], 'uses' => 'HolderController@cdnjson']);
$router->get('/v1/holder/public_keys', ['middleware' => ['cms_sign','cdn_json:holder_public_keys.json'], 'uses' => 'HolderController@cdnjson']);
$router->get('/v1/holder/nonce', ['middleware' => 'cms_sign', 'uses' => 'HolderController@nonce']);
$router->post('/v1/holder/get_test_ism', ['middleware' => 'cms_sign', 'uses' => 'HolderController@proof']);

/*
|--------------------------------------------------------------------------
| Monitoring Routes
|--------------------------------------------------------------------------
|
|
*/
$router->get('/noc/status', 'MonitoringController@status');
$router->get('/noc/time', 'MonitoringController@time');
$router->get('/noc/ping', 'MonitoringController@ping');
