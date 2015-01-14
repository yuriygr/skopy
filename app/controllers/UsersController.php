<?php

class UsersController extends ControllerBase
{
	
	public function indexAction()
	{
		$this->tag->prependTitle("Авторизация # ");
	}

	public function loginAction()
	{

		if ($this->request->isPost()) {

			$user = Users::findFirst(array(
				'login = :login: and password = :password:',
				'bind' => array(
					'login' => $this->request->getPost("login"),
					'password' => sha1($this->request->getPost("password"))
				)
			));

			if ($user === false){
				$this->flashSession->error("Данные не верны");
				return $this->dispatcher->forward(array(
					'controller' => 'users',
					'action' => 'index'
				));
			}

			$this->session->set('auth', $user->id);
			$this->session->set('auth_login', $user->login);

			$this->flashSession->success("Вы успешно вошли");
		}

		return $this->response->redirect("post");
	}

	public function logoutAction()
	{
		$this->session->remove('auth');
		$this->session->remove('auth_login');
		$this->flashSession->success("Вы успешно покинули систему");
		return $this->response->redirect("post");
	}

}