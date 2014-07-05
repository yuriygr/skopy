<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    	return $this->dispatcher->forward(array(
			'controller' => 'post',
			'action' => 'index'
		));
    }

}

