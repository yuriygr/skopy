<?php

use \Phalcon\Utils\Timeformat;

class Pages extends ModelBase
{

	public $id;

	public $title;

	public $slug;

	public $content;

	public $description;

	public $created_at;

	public $modified_in;

	public $is_draft;

	public $is_comment;

	public $is_hidden;


	public function initialize()
	{
		/*// Не записываем при редактировании сюда
		$this->skipAttributesOnUpdate(array('created_at'));

		// Не записываем при создании сюда
		$this->skipAttributesOnCreate(array('modified_in'));*/
	}

	// После того как выбрали данные из базы
	public function afterFetch()
	{	
		// Дата атомного формата
		$this->created_atom = Timeformat::atom($this->created_at);

		// Дата в приятном формате
		if ($this->created_at)
			$this->created_at = Timeformat::generate($this->created_at);

		if ($this->modified_in)
			$this->modified_in = Timeformat::generate($this->modified_in);
	}

	public function getUrl()
	{
		$url =  $this->di->getDefault()->getUrl();
		return $url->get([ 'for' => 'page-link', 'slug' => $this->slug ]);
	}
	public function getDate()
	{
		return '<time datetime="' . $this->created_atom . '">' . $this->created_at . '</time>';
	}
}
