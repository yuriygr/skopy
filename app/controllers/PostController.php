<?php

use \Phalcon\Utils\Slug as Slug; 

class PostController extends ControllerBase
{

	public function beforeExecuteRoute($dispatcher)
	{
		
		// Действия, которые защищены законом
		$restricted = array('create', 'edit', 'update', 'delete');

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
			->addJs('js/video.js')
			->addCss('css/redactor.css');

		if ($this->request->isPost()) {
			$post = new Post();
			$post->slug 		= 	$this->request->getPost('post_slug') ?
									Slug::generate($this->request->getPost('post_slug')) :
									Slug::generate($this->request->getPost('post_subject'));
			$post->subject 		= 	$this->request->getPost('post_subject');
			$post->message 		=	$this->request->getPost('post_message');
			$post->created_at 	= 	time();

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
				return $this->response->redirect("post");
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
			$this->flashSession->error("Запись не найдена");
			return $this->response->redirect("post");
		}

		$this->view->setVar('post', $post);
		
		$this->tag->prependTitle($post->subject . " - Редактирование # ");

		$this->assets
			->addJs('js/redactor.min.js')
			->addJs('js/video.js')
			->addCss('css/redactor.css');
	}

	public function updateAction()
	{
		if ($this->request->isPost()) {
			$id = $this->request->getPost('post_id');
			
			$post = Post::findFirst($id);
			$post->slug 		= 	$this->request->getPost('post_slug') ?
									Slug::generate($this->request->getPost('post_slug')) :
									Slug::generate($this->request->getPost('post_subject'));
			$post->subject 		= 	$this->request->getPost('post_subject');
			$post->message 		=	$this->request->getPost('post_message');
			$post->modified_in 	= 	time();

			if (!$post->update()) {
				foreach ($post->getMessages() as $message) {
					$this->flashSession->error((string) $message);
				}
				return $this->dispatcher->forward(array(
					'controller' => 'pages',
					'action' => 'show404'
				));
			} else {
				$this->flashSession->success("Запись успешно изменена");
				return $this->response->redirect("post");
			}
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
			return $this->response->redirect("post");
		}

		if (!$post->delete()) {
			foreach ($post->getMessages() as $message) {
				$this->flashSession->error((string) $message);
			}
			return $this->response->redirect("post");
		} else {
			$this->flashSession->success("Запись успешно удалена");
			return $this->response->redirect("post");
		}
	}

}