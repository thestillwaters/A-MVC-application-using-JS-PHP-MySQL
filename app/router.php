<?php


use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;

$collection = new RouteCollection();
session_start();

//**************************** User routing ****************************************
$collection->attachRoute(
    new Route(
        '/login',array(
            '_controller' => 'yh\a3\controller\CustomerController::indexAction',
            'methods' => 'GET',
            'name' => 'userIndex'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/customer/creating',array(
            '_controller' => 'yh\a3\controller\CustomerController::registeringAction',
            'methods' => 'GET',
            'name' => 'registering'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/customer/registerValidating',array(
            '_controller' => 'yh\a3\controller\CustomerController::registerValidatingAction',
            'methods' => 'POST',
            'name' => 'customerRegisterValidating'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/customer/created',array(
            '_controller' => 'yh\a3\controller\CustomerController::registeredAction',
            'methods' => 'POST',
            'name' => 'registered'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/customer/loginValidating/',array(
            '_controller' => 'yh\a3\controller\CustomerController::loginValidatingAction',
            'methods' => 'POST',
            'name' => 'customerLoginValidating'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/logout/',array(
            '_controller' => 'yh\a3\controller\CustomerController::logoutAction',
            'methods' => 'GET',
            'name' => 'customerLogout'
        )
    )
);
//**************************** Account routing ****************************************
$collection->attachRoute(
    new Route(
        '/', array(
            '_controller' => 'yh\a3\controller\ProductController::indexAction',
            'methods' => 'GET',
            'name' => 'productIndex'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/searching/', array(
            '_controller' => 'yh\a3\controller\ProductController::searchAction',
            'methods' => 'GET',
            'name' => 'productSearching'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/searched/', array(
            '_controller' => 'yh\a3\controller\ProductController::afterSearchAction',
            'methods' => 'POST',
            'name' => 'productSearched'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/browsing/', array(
            '_controller' => 'yh\a3\controller\ProductController::browseAction',
            'methods' => 'GET',
            'name' => 'productBrowsing'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/browsed/', array(
            '_controller' => 'yh\a3\controller\ProductController::browsedAction',
            'methods' => 'POST',
            'name' => 'stockBrowsed'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
