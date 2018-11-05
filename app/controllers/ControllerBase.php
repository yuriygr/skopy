<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{

	/**
	 * Simple return json content from array
	 * 
	 * @param  array $array
	 * @return json
	 */
	public function _returnJson($array)
	{
		$this->view->disable();
		$this->response->setContentType('application/json', 'UTF-8');
		$this->response->setJsonContent($array);
		return false;
	}

	/**
	 * Return 404 from action
	 * 
	 * @return view
	 */
	public function _notFound()
	{
		$this->dispatcher->forward([
			'controller' => 'pages',
			'action' => 'show404'
		]);
		return false;
	}
	
	/**
	 * Redirect to home route
	 * 
	 * @return response
	 */
	public function _redirectHome()
	{
		return $this->response->redirect($this->url->get([ 'for' => 'home-link' ]));
	}

	/**
	 * Redirect to home login
	 * 
	 * @return response
	 */
	public function _redirectLogin()
	{
		return $this->response->redirect($this->url->get([ 'for' => 'user-login' ]));
	}
}