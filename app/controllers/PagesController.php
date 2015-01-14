<?php

class PagesController extends ControllerBase
{
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