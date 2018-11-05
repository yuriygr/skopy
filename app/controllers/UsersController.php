<?php

class UsersController extends ControllerBase
{
	public function loginAction()
	{
		if ($this->auth->isLogin())
			return $this->_redirectHome();

		/*
		* ШАБЛОН
		*/
		$this->tag->prependTitle('Authentication');

		/**
		 * МОЮЩЕЕ СРЕДСТВО
		 */
		if ($this->request->isPost() && $this->request->isAjax() && $this->request->isSecure()) {

			try {
				
				$email 		= $this->request->getPost('email', 'email');
				$password 	= $this->request->getPost('password', 'striptags');

				if (!$email)
					throw new \Phalcon\Exception('Email is required');

				if (!$password)
					throw new \Phalcon\Exception('Password is required');

				$user = Users::findFirstByEmail($email);

				if ($user) {
					if ($this->security->checkHash($password, $user->password)) {

						$this->auth->login($user);
						
						return $this->_returnJson([ 'redirect' => $this->url->get([ 'for' => 'home-link' ]) ]);
					} else {
						throw new \Phalcon\Exception('Incorrect email or password');
					}
				} else {
					$this->security->hash(rand());
					throw new \Phalcon\Exception('Incorrect email or password');
				}

			} catch (\Phalcon\Exception $e) {
				return $this->_returnJson([ 'error' => $e->getMessage() ]);
			}

		}
	}

	public function logoutAction()
	{
		if (!$this->auth->isLogin())
			return $this->_redirectHome();

		$this->auth->logout();
		return $this->_redirectHome();
	}
}