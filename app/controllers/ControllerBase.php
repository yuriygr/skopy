<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
	public function initialize()
	{
		$this->tag->setAutoescape(false);
		$this->tag->setDocType(Phalcon\Tag::HTML5);
		$this->tag->setFavicon($this->config->site->favicon);
		$this->tag->setTitleSeparator(' - ');

		// Записываем метатеги
		$this->tag->setTitle($this->config->site->title);
		$this->tag->setDescription($this->config->site->description);
		$this->tag->setKeywords($this->config->site->keywords);

		$this->tag->setDefault('name', $this->config->site->title);

		// Записываем ассетс
		$this->assets
			 ->addJs('//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js', false)
			 ->addJs('js/jquery.share.js')
			 ->addJs('js/jquery.ambiance.js')
			 ->addJs('js/main.js');
		$this->assets
			 ->addCss('//fonts.googleapis.com/css?family=Open+Sans:300,400&amp;subset=latin,cyrillic-ext', false)
			 ->addCss('//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', false)
			 ->addCss('css/reset.css')
			 ->addCss('css/style.css');
	}
	/*
	 * Ахтунг! Возвращает json контент
	 */
	public function _returnJson($array)
	{
		$this->view->disable();
		$this->response->setContentType('application/json', 'UTF-8');
		$this->response->setJsonContent($array);
		return false;
	}
	public function _notFound()
	{
		$this->response->setStatusCode(404, "Not Found");
		$this->tag->prependTitle("Error 404");
		return $this->view->pick('index/show404');
	}

}