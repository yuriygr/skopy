<?php

use \Phalcon\Utils\Slug as Slug; 

class PortfolioController extends ControllerBase
{

	public function indexAction()
	{
		$parameter = array(
			'order' => 'id DESC'
		);
		$currentPage = $this->request->getQuery('page', 'int');
		if ($currentPage <= 0) $currentPage = 1;

		$portfolio = Portfolio::find($parameter);

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				'data' => $portfolio,
				'limit'=> $this->config->site->postLimit,
				'page' => $currentPage
			)
		);

		$portfolio = $paginator->getPaginate();
		
		if (!$portfolio->items)
			return $this->_notFound();

		$this->view->setVar('portfolios', $portfolio);

		$this->tag->prependTitle("portfolio");
	}

	public function showAction()
	{
		$id = $this->dispatcher->getParam('id');

		$parameter = array(
			'id = :id:',
			'bind' => array(
				'id' => $id
			)
		);

		$portfolio = Portfolio::findFirst($parameter);

		if (!$portfolio)
			return $this->_notFound();

		$this->view->setVar('portfolio', $portfolio);

		$this->tag->setTitle($portfolio->name);
	}

}