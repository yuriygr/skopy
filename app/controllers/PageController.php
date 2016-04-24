<?php

class PageController extends ControllerBase
{
	public function indexAction()
	{
		return $this->dispatcher->forward(array(
			'controller' => 'post',
			'action' => 'index'
		));
	}

	public function aboutAction()
	{
		$this->tag->prependTitle('about');
		$this->view->setVars([
			'contacts' 	=> $this->config->contacts
		]);
	}

	public function snakeAction()
	{
		$this->tag->prependTitle("Змейка");
	}
	
	public function show404Action()
	{
		$this->response->setStatusCode(404, "Not Found");
		$this->tag->prependTitle("Error 404");
	}
	
	public function showAction()
	{
		$slug = $this->dispatcher->getParam('slug');

		// Параметры для выборки постов
		$parameter = [ 'slug = :slug:', 'bind' => [ 'slug' => $slug ]];

		// Выбираем данные
		$page = Page::findFirst($parameter);

		// Проверка на наличие траницы
		if (!$page)
			return $this->_notFound();

		// Создаем переменные для шаблона
		$this->view->setVar('page', $page);

		// Меняем заголовок
		$this->tag->prependTitle($page->name);

		// Ну и метатеги
		if ($page->meta_description)
			$this->tag->setDescription($page->meta_description);
		if ($page->meta_keywords)
			$this->tag->setKeywords($page->meta_keywords);

		// Ну и опенграф
		$ogParameter['type'] = 'website';
		$ogParameter['site_name'] = $this->config->site->title;
		$ogParameter['title'] = $page->name;
		$ogParameter['description'] = $page->meta_description;
		$ogParameter['url'] = $this->config->site->link.$this->url->get([ 'for' => 'page-link', 'slug' => $page->slug ]);
		$this->og->input($ogParameter);
	}
}