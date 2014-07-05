<?php

$router->add('/', array(
	'controller' => 'index',
	'action' => 'index'
));


$router->notFound(array(
	"controller" => "error",
	"action" => "show404"
));