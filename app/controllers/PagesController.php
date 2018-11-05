<?php

class PagesController extends ControllerBase
{
	public function showAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		$page = Pages::findFirstBySlug($slug);

		if (!$page)
			return $this->_notFound();

		$this->view->page = $page;

		$this->tag->prependTitle($page->title);
		$this->tag->setDescription($page->description);
		$this->opengraph->input([
			'type'			=> 'page',
			'site_name'		=> $this->config->site->title,
			'title'			=> $page->title,
			'description'	=> $page->description,
			'url'			=> $this->config->site->link.$page->getUrl()
		]);
	}

	public function createAction()
	{
		$this->tag->prependTitle('Создать новую страницу');
	}

	public function editAction()
	{
		/**
		 * ШАБЛОН
		 */
		$id = $this->dispatcher->getParam('id', 'int');

		// Выбираем данные
		$page = Pages::findFirstById($id);

		// Проверка на наличие поста
		if (!$page)
			return $this->_notFound();

		// Создаем переменные для шаблона
		$this->view->setVar('page', $page);

		$this->tag->prependTitle('Изменить страницу');
	}

	public function show403Action()
	{
		$this->response->setStatusCode(403, 'Forbidden');
		$this->tag->prependTitle('Ошибка 403');
	}

	public function show404Action()
	{
		$this->response->setStatusCode(404, 'Not Found');
		$this->tag->prependTitle('Ошибка 404');
	}
}