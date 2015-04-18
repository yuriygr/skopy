<?php

class Post extends ModelBase
{

	public $id;

	public $slug;

	public $subject;

	public $short_text;

	public $full_text;

	public $created_at;

	public $modified_in;

	public $is_draft;

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
		// Дата атомного формата
		$this->created_format = date(DATE_ATOM, $this->created_at);

		// Дата в приятном формате
		if ($this->created_at)
			$this->created_at = $this->formatDate($this->created_at);

		if ($this->modified_in)
			$this->modified_in = $this->formatDate($this->modified_in);
	}

	public function the_content($type = "short")
	{
		if ($type == "short") {
			return $this->short_text;
		}
		elseif ($type == "full") {
			$text = $this->short_text;
			if ( isset($this->full_text) )
				$text .= '<hr id="readmore">' . $this->full_text;

			return $text;
		}
	}

	public function the_date()
	{
		return '<time datetime="' . $this->created_format . '" itemprop="datePublished">' . $this->created_at . '</time>';
	}
}
