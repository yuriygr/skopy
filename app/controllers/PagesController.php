<?php

use \Phalcon\Utils\Slug as Slug; 

class PagesController extends ControllerBase
{

	public function aboutAction()
	{
		$this->tag->prependTitle("Обо мне # ");
	}
	
	public function snakeAction()
	{
		$this->tag->prependTitle("Змейка # ");
	}

	public function show404Action()
	{
		$this->response->setStatusCode(404, "Not Found");
		$this->tag->prependTitle("Ошибка 404 # ");
	}

}