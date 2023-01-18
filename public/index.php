<?php
session_start();
/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/{id:\d+}');
$router->add('catalogue/index/:id/');
$router->add('fiche/index/:id/');

$router->add('profile/index');
$router->add('profile/nouveau');
$router->add('profile/modifier');
$router->add('profile/connecter');
$router->add('profile/deconnecter');

$router->add('enchere/index');
$router->add('enchere/nouveau');
$router->add('enchere/modifier/:id');
$router->add('enchere/details/:id');

$router->add('timbre/nouveau');
$router->add('timbre/modifier/:id');

$router->dispatch($_SERVER['QUERY_STRING']);
