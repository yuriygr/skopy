<?php

use \Phalcon\Utils\Timeformat;
use \Phalcon\Utils\Slug;

class Notes extends ModelBase
{

	public $id;

	public $title;

	public $slug;

	public $description;

	public $keywords;

	public $content;

	public $created_at;

	public $modified_in;

	public $is_draft;

	public $is_comment;

	public $user_id;

	public function initialize()
	{
		$this->belongsTo('user_id', 'Users', 'id', [
			'alias' => 'user'
		]);

		/*// Не записываем при редактировании сюда
		$this->skipAttributesOnUpdate(array('created_at'));

		// Не записываем при создании сюда
		$this->skipAttributesOnCreate(array('modified_in'));*/

		$this->filter =  $this->di->getDefault()->getFilter();
		$this->escaper =  $this->di->getDefault()->getEscaper();

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

	/**
	 * Setters
	 */
	public function setTitle($title)
	{
		$title = $this->filter->sanitize($title, 'striptags');
		$title = $this->escaper->escapeHtml($title);

		if (!$title)
			throw new \Phalcon\Exception('Введите заголовок');

		if (strlen($title) < 2)
			throw new \Phalcon\Exception('Заголовок очень короткий');

		$this->title = $title;
	}
	public function setSlug($slug)
	{
		$slug = $slug ?? Slug::generate($this->title);

		if (!$slug)
			throw new \Phalcon\Exception('Введите хотя бы заголовок');

		$this->slug = $slug;
	}
	public function setDescription($description)
	{
		$description = $this->filter->sanitize($description, 'striptags');
		$description = $this->escaper->escapeHtml($description);

		$this->description = $description;
	}
	public function setKeywords($keywords)
	{
		$keywords = $this->filter->sanitize($keywords, 'striptags');
		$keywords = $this->escaper->escapeHtml($keywords);

		$this->keywords = $keywords;
	}
	public function setContent($content)
	{
		if (!$content)
			throw new \Phalcon\Exception('Введите текст');

		if (strlen($content) < 10)
			throw new \Phalcon\Exception('Текст очень короткий');

		$this->content = $content;
	}

	/**
	 * Getters
	 */
	public function getDate() : string
	{
		return '<time datetime="' . $this->created_atom . '">' . $this->created_at . '</time>';
	}
}
