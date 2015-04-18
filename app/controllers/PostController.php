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
		if ($this->session->has('auth_login')) {
			$is_draft = "is_draft = 0 OR is_draft = 1";
		} else {
			$is_draft = "is_draft = 0";
		}
		$parameter = array(
			$is_draft,
			'order' => 'created_at DESC'
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

		// Чистим текст
		$string = strip_tags($post->short_text);
		$string = preg_replace('/\s+$/m', ' ', $string);
		$string = str_replace("\n",'', $string);
		$string = preg_replace('/ {2,}/',' ',$string);
		$string = substr($string, 0, 300);
		$string = rtrim($string, "!,.-");
		$string = ltrim($string);
		$string = substr($string, 0, strrpos($string, ' '));
		$string = preg_replace('#"(.*?)"#', '«$1»', $string);
		$string = $string.'...';

		$this->tag->prependTitle($post->subject . " # ");
		$this->tag->setDescription($string);
	}

	public function createAction()
	{
		$this->tag->prependTitle("Создание записи # ");

		$this->assets
			->addJs('vendors/redactor/redactor.min.js')
			->addJs('vendors/redactor/video.js')
			->addJs('vendors/redactor/readmore.js')
			->addCss('vendors/redactor/redactor.css');

		if ($this->request->isPost()) {
			$post = new Post();

			// Обрабатываем Алиас
			$post->slug			=	$this->request->getPost('post_slug');
			$post->slug			=	$this->filter->sanitize($post->slug, 'striptags');
			// Обрабатываем Заголовок
			$post->subject		=	$this->request->getPost('post_subject');
			$post->subject		=	$this->filter->sanitize($post->subject, 'striptags');
			// Создаём Алиас
			$post->slug			=	$post->slug ?
									Slug::generate($post->slug) :
									Slug::generate($post->subject);
			// Обрабатываем текст
			$text = $this->request->getPost('post_text');
			if ( preg_match( '/<hr id="readmore">/', $text) ) {
				$text = explode( '<hr id="readmore">', $text, 2 );
				$post->short_text	=	$text['0'];
				$post->full_text	=	$text['1'];
			} else {
				$post->short_text	=	$text;
				$post->full_text	=	null;
			}
			// Определяем дату создания
			$post->created_at 		=	time();
			// Определяем тип поста
			if ( $this->request->getPost('post_add') ) {
				$post->is_draft		=	0;
			} elseif ( $this->request->getPost('post_draft') ) {
				$post->is_draft		=	1;
			} else {
				$post->is_draft		=	0;
			}

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
			->addJs('vendors/redactor/redactor.min.js')
			->addJs('vendors/redactor/video.js')
			->addJs('vendors/redactor/readmore.js')
			->addCss('vendors/redactor/redactor.css');
	}

	public function updateAction()
	{
		if ($this->request->isPost()) {
			$id = $this->request->getPost('post_id');
			
			$post = Post::findFirst($id);

			// Обрабатываем Алиас
			$post->slug			=	$this->request->getPost('post_slug');
			$post->slug			=	$this->filter->sanitize($post->slug, 'striptags');
			// Обрабатываем Заголовок
			$post->subject		=	$this->request->getPost('post_subject');
			$post->subject		=	$this->filter->sanitize($post->subject, 'striptags');
			// Создаём Алиас
			$post->slug			=	$post->slug ?
									Slug::generate($post->slug) :
									Slug::generate($post->subject);
			// Обрабатываем текст
			$text = $this->request->getPost('post_text');
			if ( preg_match( '/<hr id="readmore">/', $text) ) {
				$text = explode( '<hr id="readmore">', $text, 2 );
				$post->short_text	=	$text['0'];
				$post->full_text	=	$text['1'];
			} else {
				$post->short_text	=	$text;
				$post->full_text	=	null;
			}			
			// Определяем дату изменения
			$post->modified_in 		= 	time();
			// Определяем тип поста
			if ( $this->request->getPost('post_add') ) {
				$post->is_draft		=	0;
			} elseif ( $this->request->getPost('post_draft') ) {
				$post->is_draft		=	1;
			} else {
				$post->is_draft		=	0;
			}			

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