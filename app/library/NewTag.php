<?php

namespace Phalcon;

class NewTag extends \Phalcon\Tag
{
	static $favicon = null;
	static $description = null;
	static $keywords = null;
	static $generator = null;

	public static function getCharset()
	{
		return '<meta charset="utf-8">'."\r\n";
	}

	/*
		Favicon
	*/
	public function setFavicon($param)
	{
		self::$favicon = $param;
	}
	public static function getFavicon()
	{
		if (self::$favicon != null)
			return '<link rel="shortcut icon" type="image/x-icon" href="' . self::$favicon . '">'."\r\n";
	}
	/*
		Description
	*/
	public function setDescription($param)
	{
		self::$description =  self::_cleanText($param);
	}
	public static function getDescription()
	{
		if (self::$description != null)
			return '<meta name="description" content="' . self::$description . '">'."\r\n";
	}
	/*
		KeyWords
	*/
	public function setKeywords($param)
	{
		self::$keywords =  self::_cleanText($param);
	}
	public static function getKeywords()
	{
		if (self::$keywords != null)
			return '<meta name="keywords" content="' . self::$keywords . '">'."\r\n";
	}
	/*
		Generator
	*/
	public function setGenerator($param)
	{
		self::$generator =  self::_cleanText($param);
	}
	public static function getGenerator()
	{
		if (self::$generator != null)
			return '<meta name="generator" content="' . self::$generator . '">'."\r\n";
	}

	private function _cleanText($string)
	{
		$filter =  \Phalcon\DI\FactoryDefault::getDefault()->getShared('filter');
		$string = $filter->sanitize($string, 'striptags');
		$string = preg_replace('/\s+$/m', ' ', $string);
		$string = str_replace("\n",'', $string);
		$string = preg_replace('/ {2,}/',' ',$string);

		return $string;
	}
}