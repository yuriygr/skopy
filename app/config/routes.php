<?php

$router->add('/', 'Notes::list')->setName('home-link');

/** Notes */
$router->add('/p', 					'Notes::list')->setName('notes-list');
$router->add('/p/?page={page}', 	'Notes::list')->setName('notes-page');
$router->add('/p/{slug}', 			'Notes::show')->setName('notes-link');
$router->add('/p/create', 			'Notes::create')->setName('notes-create');
$router->add('/p/{id}/edit', 		'Notes::edit')->setName('notes-edit');
$router->add('/p/{id}/delete', 		'Notes::delete')->setName('notes-delete');

/** Pages */
$router->add('/page/{slug}', 		'Pages::show')->setName('page-link');
$router->add('/page/create', 		'Pages::create')->setName('page-create');
$router->add('/page/{id}/edit', 	'Pages::edit')->setName('page-edit');
$router->add('/page/{id}/delete', 	'Pages::delete')->setName('page-delete');

/** Users */
$router->add('/u/login', 			'Users::login')->setName('user-login');
$router->add('/u/logout', 			'Users::logout')->setName('user-logout');
$router->add('/u/create', 			'Users::create')->setName('user-create');
$router->add('/u/{id}/edit', 		'Users::edit')->setName('user-edit');
$router->add('/u/{id}/delete', 		'Users::delete')->setName('user-delete');

$router->notFound('Pages::show404');