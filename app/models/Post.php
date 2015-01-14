<?php

class Post extends ModelBase
{

	public $id;

	public $slug;

	public $subject;

	public $message;

	public $created_at;

	public $modified_in;


	public function initialize()
	{
		// Не записываем при редактировании сюда
		$this->skipAttributesOnUpdate(array('created_at'));

		// Не записываем при создании сюда
		$this->skipAttributesOnCreate(array('modified_in'));
	}

	// После того как выбрали данные из базы
	public function afterFetch()
	{	
		if ($this->modified_in)
			$this->modified_in = $this->formatDate($this->modified_in);

		if ($this->created_at)
			$this->created_at = $this->formatDate($this->created_at);
	}
}
