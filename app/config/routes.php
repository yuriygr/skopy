<?php

$router->removeExtraSlashes(true);

$router->add('/', array(
	'controller' => 'post',
	'action' => 'index'
));


/*Посты*/
$router->add('/p/', array(
	'controller' => 'post',
	'action' => 'index'
));
$router->add('/p/:action', array(
	'controller' => 'post',
	'action' => 'show',
	'slug' => 1
));
$router->add('/p/create', array(
	'controller' => 'post',
	'action' => 'create'
));
$router->add('/p/:action/edit', array(
	'controller' => 'post',
	'action' => 'edit',
	'slug' => 1
));
$router->add('/p/:action/delete', array(
	'controller' => 'post',
	'action' => 'delete',
	'slug' => 1
));


/*Страницы*/
$router->add('/snake', array(
	'controller' => 'pages',
	'action' => 'snake'
));


$router->notFound(array(
	'controller' => 'error',
	'action' => 'show404'
));
