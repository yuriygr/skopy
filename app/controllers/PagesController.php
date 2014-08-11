<?php

class PagesController extends ControllerBase
{

	public function show404Action()
	{
		$this->response->setStatusCode(404, "Not Found");

		$type = $this->dispatcher->getParam("type");
		switch ($type) {
			case 'noPosts':
				# code...
				break;
			case 'no':
				# code...
				break;

			default:
				# code...
				break;
		}
		$this->tag->prependTitle("404 # ");
	}

	public function show403Action()
	{
		$this->response->setStatusCode(403, "Forbidden");

		$type = $this->dispatcher->getParam("type");
		$this->tag->prependTitle("403 # ");
	}
}