<?php

class UsersController extends ControllerBase
{
	public function indexAction()
	{
		return $this->response->redirect("u/login");
	}

	public function loginAction()
	{
		$this->tag->prependTitle("Авторизация");
		if ($this->session->has('auth_login'))
			return $this->response->redirect("post");
	}

	public function logoutAction()
	{
		$this->session->remove('auth');
		$this->session->remove('auth_login');
		$this->flashSession->success("Вы успешно покинули систему");
		return $this->response->redirect($this->url->get([ 'for' => 'home-link' ]));
	}

	public function authAction()
	{

		if ( $this->request->isPost() && $this->request->isAjax() ) {
			$e = new Phalcon\Escaper();

			$login 		= 	$this->request->getPost('login');
			$login		=	$this->filter->sanitize($login, 'striptags');
			$kasumi 	=	$e->escapeHtml($login);

			$password 	= 	$this->request->getPost('password');
			$password	=	$this->filter->sanitize($password, 'striptags');

			$user = Users::findFirstByLogin($login);
			
			if (!$login)
				return $this->_returnJson([ 'error' => 'Введите логин' ]);

			if ($user) {
				if ($this->security->checkHash($password, $user->password)) {
					$this->session->set('auth', $user->id);
					$this->session->set('auth_login', $user->login);
						
					return $this->_returnJson([ 'redirect' => $this->url->get([ 'for' => 'home-link' ]) ]);
				} else {
					return $this->_returnJson([ 'error' => 'Пароль не верен' ]);
				}
			} else {
				return $this->_returnJson([ 'error' => 'Пользователь с таким логином не найден' ]);
			}
		}

		return $this->response->redirect($this->url->get([ 'for' => 'home-link' ]));
	}

	public function showAction()
	{
		$login = $this->dispatcher->getParam('login');

		// Параметры для выборки пользователя
		$parameter = ['login = :login:', 'bind' => ['login' => $login]];

		// Выбираем данные
		$user = Users::findFirst($parameter);

		// Проверка на наличие пользователя
		if (!$user)
			return $this->_notFound();

		// Создаем переменные для шаблона
		$this->view->setVar('user', $user);

		// Создаем заголовок
		$this->tag->prependTitle($user->name);
	}

	public function settingAction()
	{
		$login = $this->session->get('auth_login');

		// Параметры для выборки пользователя
		$parameter = ['login = :login:', 'bind' => ['login' => $login]];

		// Выбираем данные
		$user = Users::findFirst($parameter);

		// Проверка на наличие пользователя
		if (!$user)
			return $this->_notFound();

		// Создаем переменные для шаблона
		$this->view->setVar('user', $user);

		// Создаем заголовок
		$this->tag->prependTitle('Настройки профиля');
	}
}