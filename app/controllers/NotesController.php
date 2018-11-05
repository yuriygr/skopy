<?php

use \Phalcon\Utils\Slug;

class NotesController extends ControllerBase
{
	public function listAction()
	{
		$currentPage = $this->request->getQuery('page', 'int', 1);

		if ($this->auth->isLogin())
			$is_draft = "is_draft = 0 OR is_draft = 1";
		else
			$is_draft = "is_draft = 0";

		// Выбираем данные
		$note = Notes::find([
			$is_draft,
			'order' => 'created_at DESC'
		]);

		if (!$note)
			return $this->_notFound();

		// Разделяем на страницы
		$paginator = new \Phalcon\Paginator\Adapter\Model([
			'data' 	=> $note,
			'limit'	=> $this->config->site->postLimit,
			'page' 	=> $currentPage
		]);
		$notes = $paginator->getPaginate();

		$this->view->notes = $notes;

		$this->tag->prependTitle('Заметки');
		$this->tag->setDescription($this->config->site->description);
		$this->opengraph->input([
			'site_name' => $this->config->site->title,
			'type' => 'website',
			'title' => 'Заметки',
			'description' => $this->config->site->description
		]);
	}

	public function showAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		$note = Notes::findFirstByslug($slug);

		if (!$note)
			return $this->_notFound();

		$this->view->note = $note;

		$this->tag->prependTitle($note->title);
		$this->tag->setDescription($note->description);
		$this->opengraph->input([
			'site_name' => $this->config->site->title,
			'type' => 'article',
			'title' => $note->slug,
			'description' => $note->description
		]);
	}

	public function createAction()
	{
		/**
		 * ШАБЛОН
		 */
		// Устанавливаем заголовок
		$this->tag->prependTitle('Создать заметку');


		/**
		 * МОЮЩЕЕ СРЕДСТВО
		 */
		if ($this->request->isPost() && $this->request->isAjax() && $this->request->isSecure()) {

			try {
				$title = $this->request->getPost('title', 'string');
				$slug = $this->request->getPost('slug', 'string');
				$description = $this->request->getPost('description');
				$keywords = $this->request->getPost('keywords');
				$content = $this->request->getPost('content');
				$is_draft = $this->request->getPost('is_draft', 'int', 0);
				$is_comment = $this->request->getPost('is_comment', 'int', 0);
				$user_id = $this->auth->getId();

				$note = new Notes();
				
				$note->setTitle($title);
				$note->setSlug($slug);
				$note->setDescription($description);
				$note->setKeywords($keywords);
				$note->setContent($content);

				$note->is_draft 	= $is_draft;
				$note->is_comment 	= $is_comment;

				$note->created_at 	= time();
				$note->modified_in 	= time();

				$note->user_id = $user_id;

				// Если пост прошёл
				if (!$note->save()) {
					throw new \Phalcon\Exception('Заметка не добавилась. ' . $note->getMessages()[0]);
				}

				return $this->_returnJson([
					'status' => 'success',
					'message' => 'Заметка успешно создана, перенаправляю к ней',
					'redirect' => $this->url->get([ 'for' => 'notes-link', 'slug' => $note->slug ])
				]);

			} catch (\Phalcon\Exception $e) {
				return $this->_returnJson([ 'status' => 'error', 'message' => $e->getMessage() ]);
			}
		}
	}

	public function editAction()
	{
		/**
		 * ШАБЛОН
		 */
		$id = $this->dispatcher->getParam('id', 'int');

		// Выбираем данные
		$note = Notes::findFirstById($id);

		// Проверка на наличие поста
		if (!$note)
			return $this->_notFound();

		// Создаем переменные для шаблона
		$this->view->setVar('note', $note);

		// Устанавливаем заголовок
		$this->tag->prependTitle('Изменить заметку');

		/**
		 * МОЮЩЕЕ СРЕДСТВО
		 */
		if ($this->request->isPost() && $this->request->isAjax() && $this->request->isSecure()) {

			try {

				// Название заметки
				$title = $this->request->getPost('title', 'striptags');
				if (!$title)
					throw new \Phalcon\Exception('Введите заголовок');

				// Получаем текст
				$content = $this->request->getPost('content');
				if (!$content)
					throw new \Phalcon\Exception('Введите текст');

				// Обрабатываем Slug
				$slug = $this->request->getPost('slug', 'string') ?? Slug::generate($title);
				if (!$slug)
					throw new \Phalcon\Exception('Введите хотя бы заголовок');

				// Обрабатываем описание
				$description = $this->request->getPost('description');
				$description = $this->filter->sanitize($description, 'striptags');
				$description = $this->escaper->escapeHtml($description);

				// Черновик или нет
				$is_draft = $this->request->getPost('is_draft', 'int', 0);

				// Включены ли комментарии
				$is_comment = $this->request->getPost('is_comment', 'int', 0);

				// Сохраняем контент
				$note->title 		= $title;
				$note->content 		= $content;

				$note->slug 		= $slug;
				$note->description 	= $description;

				$note->is_draft 	= $is_draft;
				$note->is_comment 	= $is_comment;

				$note->created_at 	= time();
				$note->modified_in 	= time();

				// Если пост прошёл
				if (!$note->save())
					throw new \Phalcon\Exception('Заметка не сохранилась. ' . $note->getMessages()[0]);

				return $this->_returnJson([
					'status' => 'success',
					'message' => 'Заметка успешно отредактирована, перенаправляю к ней',
					'redirect' => $this->url->get([ 'for' => 'notes-link', 'slug' => $note->slug ])
				]);	

			} catch (\Phalcon\Exception $e) {
				return $this->_returnJson([ 'status' => 'error', 'message' => $e->getMessage() ]);
			}
		}
	}

	public function deleteAction()
	{
		$id = $this->dispatcher->getParam('id', 'int');

		// Выбираем данные
		$note = Notes::findFirstById($id);

		if (!$note)
			return $this->_notFound();

		if ($note->delete()) {
			$this->flashSession->success("Заметка успешно удалена");
			return $this->response->redirect($this->url->get([ 'for' => 'home-link' ]));
		}
	}
}