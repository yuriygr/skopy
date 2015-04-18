<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
	public function onConstruct()
	{
		$this->tag->setAutoescape(false);
		$this->tag->setDocType(Phalcon\Tag::HTML5);

		// Записываем метатеги
		$this->tag->setTitle($this->config->site->title);
		$this->tag->setDescription($this->config->site->description);
		$this->tag->setKeywords($this->config->site->keywords);

		$this->tag->setDefault('name', $this->config->site->title);

		$this->assets
			->addJs('//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', false)
			->addJs('js/main.js');
		$this->assets
			->addCss('//fonts.googleapis.com/css?family=Open+Sans:700,300,400&subset=latin,cyrillic-ext', false)
			->addCss('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', false)
			->addCss('css/reset.css')
			->addCss('css/style.css');
	}
}