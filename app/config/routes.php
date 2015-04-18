<?php

$router->removeExtraSlashes(true);

$router->add('/', array(
	'controller' => 'post',
	'action' => 'index'
));

// Post
$blog = new \Phalcon\Mvc\Router\Group(array( 'controller' => 'post' ));
$blog->setPrefix('/p');
$blog->add('', array( 'action' => 'index' ));
$blog->add('/:action', array( 'action' => 'show', 'slug' => 1 ));
$blog->add('/create', array( 'action' => 'create' ));
$blog->add('/:action/edit', array( 'action' => 'edit', 'slug' => 1 ));
$blog->add('/:action/delete', array( 'action' => 'delete', 'slug' => 1 ));
$router->mount($blog);


// Pages
$pages = new \Phalcon\Mvc\Router\Group(array( 'controller' => 'pages' ));
$pages->add('/about', array( 'action' => 'about' ));
$pages->add('/snake', array( 'action' => 'snake' ));
$router->mount($pages);

$router->notFound(array(
	'controller' => 'pages',
	'action' => 'show404'
));