<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
	public function onConstruct()
	{
	$this->tag->setAutoescape(false);
	$this->tag->setDocType(Phalcon\Tag::HTML5);
	$this->tag->setTitle($this->config->site->title);
	$this->tag->setDefault('description', '<meta name="description" content="'.$this->config->site->description.'">'."\n\r");
	$this->tag->setDefault('keywords', '<meta name="keywords" content="'.$this->config->site->keywords.'">'."\n\r");

	$this->assets
		->addJs('js/jquery-2.1.1.min.js')
		->addCss('http://fonts.googleapis.com/css?family=Open+Sans:700,300,400&subset=latin,cyrillic-ext', false)
		->addCss('css/font-awesome.min.css')
		->addCss('css/reset.css')
		->addCss('css/style.css');
	}
}