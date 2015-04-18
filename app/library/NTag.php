<?php

namespace Phalcon;

class NTag extends \Phalcon\Tag
{
	static $description = null;
	static $keywords = null;

	public function setDescription($param)
	{
		self::$description = $param;
	}
	public static function getDescription()
	{
		if (self::$description != null)
			return '<meta name="description" content="'.self::$description.'">'."\r\n";
	}

	public function setKeywords($param)
	{
		self::$keywords = $param;
	}
	public static function getKeywords()
	{
		if (self::$keywords != null)
			return '<meta name="keywords" content="'.self::$keywords.'">'."\r\n";
	}
}