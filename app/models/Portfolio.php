<?php

class Portfolio extends ModelBase
{

	public $id;

	public $name;

	public $intro;

	public $description;

	public $image;

	public $link;


	public function initialize()
	{
	}

	public function afterFetch()
	{	
	}
}
