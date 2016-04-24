<?php

$router->removeExtraSlashes(true);

$router->add('/', 'Post::index' )
	   ->setName('home-link');

$router->add('/post','Post::index')
	   ->setName('post-home');
# Обо мне
$router->add('/about', 'Page::about')
	   ->setName('page-about');
# Змейка
$router->add('/snake', 'Page::snake')
	   ->setName('page-snake');

/*
	Posts
	=========================================================
*/
$blog = new \Phalcon\Mvc\Router\Group([ 'controller' => 'post' ]);
$blog->setPrefix('/p');
# Ссылка
$blog->add('/{slug}', [ 'action' => 'show' ])
	 ->setName('post-link');
# Создание
$blog->add('/create', [ 'action' => 'create' ])
	 ->setName('post-create');
# Редактирование
$blog->add('/{slug}/edit', [ 'action' => 'edit' ])
	 ->setName('post-edit');
# Изменение
$blog->add('/{slug}/update', [ 'action' => 'update' ])
	 ->setName('post-update');
# Удаление
$blog->add('/{slug}/delete', [ 'action' => 'delete' ])
	 ->setName('post-delete');
# Загрузка
$blog->add('/upload', [ 'action' => 'upload' ])
	 ->setName('post-upload');
$router->mount($blog);


/*
	Portfolio
	=========================================================
*/
$portfolio = new \Phalcon\Mvc\Router\Group([ 'controller' => 'portfolio' ]);
$portfolio->setPrefix('/portfolio');
# Главная
$portfolio->add('', [ 'action' => 'index' ])
		  ->setName('portfolio-home');
# Ссылка
$portfolio->add('/{id}', [ 'action' => 'show' ])
		  ->setName('portfolio-link');
$router->mount($portfolio);

/*
	Pages
	=========================================================
*/
$page = new \Phalcon\Mvc\Router\Group([ 'controller' => 'page' ]);
$page->setPrefix('/page');
# Ссылка
$page->add('/{slug}', [ 'action' => 'show' ])
	 ->setName('page-link');
$router->mount($page);



/*
	Users
	=========================================================
*/
$user = new \Phalcon\Mvc\Router\Group([ 'controller' => 'users' ]);
$user->setPrefix('/u');
# Главная
$user->add('', [ 'action' => 'index' ]);
# Вход
$user->add('/login', [ 'action' => 'login' ])
	 ->setName('user-login');
# Выход
$user->add('/logout', [ 'action' => 'logout' ])
	 ->setName('user-logout');
# Авторизация
$user->add('/auth', [ 'action' => 'auth' ])
	 ->setName('user-auth');
# Ссылка
$user->add('/profile-{login}', [ 'action' => 'show' ])
	 ->setName('user-profile');
# Настройки
$user->add('/setting', [ 'action' => 'setting'])
	 ->setName('user-setting');
$router->mount($user);

/*
	404 page
	=========================================================
*/
$router->notFound( 'Page::show404' );