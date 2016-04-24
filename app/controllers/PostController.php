<?php

use \Phalcon\Mvc\View as View;
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
		if ($this->session->has('auth_login'))
			$is_draft = "is_draft = 0 OR is_draft = 1";
		else
			$is_draft = "is_draft = 0";

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

		// Проверка на наличие поста
		if (!$post->items)
			return $this->_notFound();

		// Создаем переменные для шаблона
		$this->view->setVar('posts', $post);

		// Устанавливаем заголовок
		$this->tag->prependTitle("Посты");

		// Ну и опенграф
		$ogParameter['type'] = 'website';
		$ogParameter['site_name'] = $this->config->site->title;
		$ogParameter['title'] = "Посты - " . $this->config->site->title;
		$ogParameter['description'] = $this->config->site->description;
		$ogParameter['url'] = $this->config->site->link;
		$this->og->input($ogParameter);
	}

	public function showAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		// Параметры для выборки постов
		$parameter = [ 'slug = :slug:', 'bind' => [ 'slug' => $slug ]];

		// Выбираем данные
		$post = Post::findFirst($parameter);

		// Проверка на наличие поста
		if (!$post)
			return $this->_notFound();

		// Создаем переменные для шаблона
		$this->view->setVar('post', $post);

		// Меняем заголовок
		$this->tag->setTitle($post->subject);

		// Ну и метатеги
		if ($post->meta_description)
			$this->tag->setDescription($post->meta_description);
		if ($post->meta_keywords)
			$this->tag->setKeywords($post->meta_keywords);

		// Ну и опенграф
		$ogParameter['type'] = 'article';
		$ogParameter['site_name'] = $this->config->site->title;
		$ogParameter['title'] = $post->subject;
		$ogParameter['description'] = $post->meta_description;
		$ogParameter['url'] = $this->config->site->link.$this->url->get([ 'for' => 'post-link', 'slug' => $post->slug ]);
		$this->og->input($ogParameter);
	}

	public function createAction()
	{
		$this->tag->prependTitle("Создание поста");

		$this->assets
			 ->addCss('vendors/redactor/redactor.css')
			 ->addJs('vendors/redactor/redactor.min.js')
			 ->addJs('vendors/redactor/video.js')
			 ->addJs('vendors/redactor/readmore.js');

		if ($this->request->isPost() && $this->request->isAjax()) {
			$post = new Post();
			$escaper = new \Phalcon\Escaper();

			// Обрабатываем Алиас
			$post->slug			=	$this->request->getPost('post_slug');
			$post->slug			=	$this->filter->sanitize($post->slug, 'striptags');
			$post->slug			=	$escaper->escapeHtml($post->slug);
			// Обрабатываем Заголовок
			$post->subject		=	$this->request->getPost('post_subject');
			$post->subject		=	$this->filter->sanitize($post->subject, 'striptags');
			$post->subject		=	$escaper->escapeHtml($post->subject);
			// Создаём Алиас
			$post->slug			=	$post->slug ?
									Slug::generate($post->slug) :
									Slug::generate($post->subject);
			// Обрабатываем текст
			$text = $this->request->getPost('post_text');
			if (preg_match( '/<hr id="readmore">/', $text)) {
				$text = explode( '<hr id="readmore">', $text, 2 );
				$post->short_text	=	$text['0'];
				$post->full_text	=	$text['1'];
			} else {
				$post->short_text	=	$text;
				$post->full_text	=	null;
			}
			// Определяем дату создания
			$post->created_at 		=	time();

			// Обрабатываем описание
			$post->meta_description		=	$this->request->getPost('post_meta_description');
			$post->meta_description		=	$this->filter->sanitize($post->meta_description, 'striptags');
			$post->meta_description		=	$escaper->escapeHtml($post->meta_description);
			// Обрабатываем ключевые слова
			$post->meta_keywords		=	$this->request->getPost('post_meta_keywords');
			$post->meta_keywords		=	$this->filter->sanitize($post->meta_keywords, 'striptags');
			$post->meta_keywords		=	$escaper->escapeHtml($post->meta_keywords);

			// Определяем тип поста
			if ($this->request->getPost('post_add')) {
				$post->is_draft		=	0;
			} elseif ($this->request->getPost('post_draft')) {
				$post->is_draft		=	1;
			} else {
				$post->is_draft		=	0;
			}



			// Проверка на наличие заголовка
			if (!$post->subject)
				return $this->_returnJson([ 'error' => 'Введите заголовок' ]);

			// Проверка на наличие текста
			if (!$post->short_text)
				return $this->_returnJson([ 'error' => 'Введите текст' ]);
			
			// Если пост не прошёл
			if (!$post->save())
				return $this->_returnJson([ 'error' => 'Пост не добавился' ]);
			else 
				return $this->_returnJson([
					'success' => 'Запись успешно создана, перенаправляю',
					'redirect' => $this->url->get([ 'for' => 'post-link', 'slug' => $post->slug ])
				]);
		}
	}

	public function editAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		$parameter = [ 'slug = :slug:', 'bind' => [ 'slug' => $slug ]];

		$post = Post::findFirst($parameter);

		if (!$post)
			return $this->_notFound();

		$this->view->setVar('post', $post);

		$this->tag->prependTitle("Редактирование: " . $post->subject);

		$this->assets
			 ->addJs('vendors/redactor/redactor.min.js')
			 ->addJs('vendors/redactor/video.js')
			 ->addJs('vendors/redactor/readmore.js')
			 ->addCss('vendors/redactor/redactor.css');
	}

	public function updateAction()
	{
		if ($this->request->isPost()) {
			$slug = $this->dispatcher->getParam('slug');

			$parameter = [ 'slug = :slug:', 'bind' => [ 'slug' => $slug ]];

			$post = Post::findFirst($parameter);
			$escaper = new \Phalcon\Escaper();

			// Обрабатываем Алиас
			$post->slug			=	$this->request->getPost('post_slug');
			$post->slug			=	$this->filter->sanitize($post->slug, 'striptags');
			$post->slug			=	$escaper->escapeHtml($post->slug);
			// Обрабатываем Заголовок
			$post->subject		=	$this->request->getPost('post_subject');
			$post->subject		=	$this->filter->sanitize($post->subject, 'striptags');
			$post->subject		=	$escaper->escapeHtml($post->subject);
			// Создаём Алиас
			$post->slug			=	$post->slug ?
									Slug::generate($post->slug) :
									Slug::generate($post->subject);
			// Обрабатываем текст
			$text = $this->request->getPost('post_text');
			if (preg_match( '/<hr id="readmore">/', $text)) {
				$text = explode( '<hr id="readmore">', $text, 2 );
				$post->short_text	=	$text['0'];
				$post->full_text	=	$text['1'];
			} else {
				$post->short_text	=	$text;
				$post->full_text	=	null;
			}			
			// Определяем дату изменения
			$post->modified_in 		= 	time();

			// Обрабатываем описание
			$post->meta_description		=	$this->request->getPost('post_meta_description');
			$post->meta_description		=	$this->filter->sanitize($post->meta_description, 'striptags');
			$post->meta_description		=	$escaper->escapeHtml($post->meta_description);
			// Обрабатываем ключевые слова
			$post->meta_keywords		=	$this->request->getPost('post_meta_keywords');
			$post->meta_keywords		=	$this->filter->sanitize($post->meta_keywords, 'striptags');
			$post->meta_keywords		=	$escaper->escapeHtml($post->meta_keywords);
			
			// Определяем тип поста
			if ($this->request->getPost('post_add')) {
				$post->is_draft		=	0;
			} elseif ($this->request->getPost('post_draft')) {
				$post->is_draft		=	1;
			} else {
				$post->is_draft		=	0;
			}			

			if (!$post->update()) {
				foreach ($post->getMessages() as $message)
					$this->flashSession->error((string) $message);

				return $this->response->redirect($this->url->get([ 'for' => 'post-edit', 'slug' => $post->slug ]));
			} else {
				$this->flashSession->success("Запись успешно изменена");
				return $this->response->redirect($this->url->get([ 'for' => 'post-link', 'slug' => $post->slug ]));
			}
		}
	}

	public function deleteAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		$parameter = [ 'slug = :slug:', 'bind' => [ 'slug' => $slug ]];

		$post = Post::findFirst($parameter);

		if (!$post)
			return $this->_notFound();

		if (!$post->delete()) {
			foreach ($post->getMessages() as $message)
				$this->flashSession->error((string) $message);

			return $this->response->redirect($this->url->get([ 'for' => 'home-link' ]));
		} else {
			$this->flashSession->success("Запись успешно удалена");
			return $this->response->redirect($this->url->get([ 'for' => 'post-home' ]));
		}
	}
}