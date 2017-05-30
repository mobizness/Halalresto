<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'index', and we pass a param to select the view file
 * 
 */
    Router::connect('/popular', array('controller' => 'users', 'action' => 'login', 'admin' => true));
    Router::connect('/admin', array('controller' => 'users', 'action' => 'login', 'admin' => true));
	Router::connect('/foodordersysadmin', array('controller' => 'users', 'action' => 'login', 'admin' => true));
    Router::connect('/restaurant', array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
    Router::connect('/customer', array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));   
    //Remove /dev and replace it with /
    Router::connect('/', array('controller' => 'searches', 'action' => 'homepage'));
    Router::connect('/dev', array('controller' => 'searches', 'action' => 'index'));
    
    Router::connect('/signup', array('controller' => 'users', 'action' => 'signup'));
    Router::connect('/restaurantSignup', array('controller' => 'users', 'action' => 'storeSignup'));
    Router::connect('/contactUs', array('controller' => 'contactuses', 'action' => 'contactUs'));
    Router::connect('/customerlogin', array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
    Router::connect('/shop/:storename/:id', array('controller' => 'searches', 'action' => 'storeitems') ,
    										 array('pass' => array('storename','id')));

    Router::connect('/city/:cityName/:city', array('controller' => 'searches', 'action' => 'stores') ,
                                             array('pass' => array('cityName', 'city')));

    Router::connect('/city/:cityName/:areaName/:city/:area', array('controller' => 'searches', 'action' => 'stores') ,
                                             array('pass' => array('cityName', 'city', 'areaName', 'area')));

    Router::connect('/address', array('controller' => 'searches', 'action' => 'searchByAddress'));

    Router::connect('/apply', array('controller' => 'users', 'action' => 'apply'));

    Router::connect('/faq', array('controller' => 'pages', 'action' => 'faq'));
    Router::connect('/aboutus', array('controller' => 'pages', 'action' => 'aboutus'));
    Router::connect('/cgv', array('controller' => 'pages', 'action' => 'cgv'));
    Router::connect('/notice', array('controller' => 'pages', 'action' => 'notice'));
    Router::connect('/guide', array('controller' => 'pages', 'action' => 'guide'));

    
/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
	
