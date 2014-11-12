<?php

use \Phalcon\Utils\Slug as Slug; 

class PostController extends ControllerBase
{

	/**
	 * So if we want to check if the User has access to Post::createAction(),
	 * all we need to do is to check if matching session variable exists and contains
	 * expected value. (Keep in mind that this “authorization system” is very simple)
	 */
	public function beforeExecuteRoute($dispatcher)
	{
		
		// Действия, которые защищены законом
		$restricted = array('create', 'edit', 'delete');

		//auth token
		$auth = $this->session->get('auth');

		//we check here if currently invoked action is restricted and if
		//the user is logged in
		if (in_array($dispatcher->getActionName(), $restricted) && !$auth) {

			$this->flashSession->error("У вас нет доступа к этому модулю");
			return $this->dispatcher->forward(array(
				'controller' => 'post',
				'action' => 'index'
			));
		}
	}

	/**
	 * We simply pass all the Post created to the view
	 */
	public function indexAction()
	{
		$parameter = array(
			'order' => 'id DESC'
		);
		$currentPage = $this->request->getQuery('page', 'int');
		if ($currentPage <= 0) $currentPage = 1;

		$post = Post::find($parameter);

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				'data' => $post,
				'limit'=> $this->config->site->postLimit,
				'page' => $currentPage
			)
		);

		// Получение результатов работы пагинатора
		$post = $paginator->getPaginate();
		
		if (!$post->items) {
			return $this->dispatcher->forward(array(
				'controller' => 'pages',
				'action' => 'show404'
			));
		}

		$this->view->setVar('posts', $post);

		$this->tag->prependTitle("Блог # ");
	}

	/**
	 * Let’s read that record from the database. When using MySQL adapter,
	 * like we do in this tutorial, $id variable will be escaped so
	 * we don’t have to deal with it.
	 */
	public function showAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		$parameter = array(
			'slug = :slug:',
			'bind' => array(
				'slug' => $slug
			)
		);

		$post = Post::findFirst($parameter);

		if (!$post) {
			return $this->dispatcher->forward(array(
				'controller' => 'pages',
				'action' => 'show404'
			));
		}

		$this->view->setVar('post', $post);
		
		$this->tag->prependTitle($post->subject . " # ");
	}

	public function createAction()
	{
		$this->tag->prependTitle("Создание записи # ");

		$this->assets
			->addJs('js/redactor.min.js')
			->addCss('css/redactor.css');

		if ($this->request->isPost()) {
			$post = new Post();
			$post->slug 		= 	Slug::generate($this->request->getPost('post_subject'));
			$post->subject 		= 	$this->request->getPost('post_subject');
			$post->message 		=	$this->request->getPost('post_message');
			$post->timestamp 	= 	time();

			if (!$post->save()) {
				foreach ($post->getMessages() as $message) {
					$this->flash->error((string) $message);
				}
				return $this->dispatcher->forward(array(
					'controller' => 'post',
					'action' => 'create'
				));
			} else {
				$this->flashSession->success("Запись успешно создана");
				return $this->response->redirect("post/index");
			}
		}
	}

	public function editAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		$parameter = array(
			'slug = :slug:',
			'bind' => array(
				'slug' => $slug
			)
		);

		$post = Post::findFirst($parameter);

		if (!$post) {
			return $this->dispatcher->forward(array(
				'controller' => 'pages',
				'action' => 'show404'
			));
		}

		$this->view->setVar('post', $post);
		
		$this->tag->prependTitle($post->subject . " - Изменить # ");

		if ($this->request->isPost()) {
			$post = Post::findFirst($id);
			$post->name = "RoboCop";
			$post->save();
		}
	}

	public function deleteAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		$parameter = array(
			'slug = :slug:',
			'bind' => array(
				'slug' => $slug
			)
		);

		$post = Post::findFirst($parameter);

		if (!$post) {
			$this->flashSession->error("Запись не найдена");
			return $this->response->redirect("post/index");
		}

		if (!$post->delete()) {
			foreach ($post->getMessages() as $message) {
				$this->flashSession->error((string) $message);
			}
			return $this->response->redirect("post/index");
		} else {
			$this->flashSession->success("Запись успешно удалена");
			return $this->response->redirect("post/index");
		}
	}

}