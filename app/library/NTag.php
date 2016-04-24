<?php

namespace Phalcon;

class NTag extends \Phalcon\Tag
{
	static $favicon = null;
	static $description = null;
	static $keywords = null;
	static $well = null;

	public static function getCharset()
	{
		return '<meta charset="utf-8">'."\r\n";
	}

	public function setFavicon($param)
	{
		self::$favicon = $param;
	}
	public static function getFavicon()
	{
		if (self::$favicon != null)
			return '<link rel="shortcut icon" href="' . self::$favicon . '" type="image/x-icon">'."\r\n";
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

	public function setWell($param)
	{
		self::$well = $param;
	}
	public static function getWell()
	{
		if (self::$well != null)
			return self::$well;
	}


	public function menuLink($param)
	{
		$router =  \Phalcon\DI\FactoryDefault::getDefault()->getShared('router');
		$currentPage = $router->getRewriteUri();
		$linkCurrent = ($currentPage == $param[0]) ? 'active' : null ;

		if (isset($param['class']))
			$param['class'] = $param['class'] . ' ' . $linkCurrent;
		else
			$param['class'] = $linkCurrent;

		return self::linkTo( $param );
	}


	private function _cleanText($string)
	{
		// Чистим текст
		$filter =  \Phalcon\DI\FactoryDefault::getDefault()->getShared('filter');
		$string = $filter->sanitize($string, 'striptags');
		$string = preg_replace('/\s+$/m', ' ', $string);
		$string = str_replace("\n",'', $string);
		$string = preg_replace('/ {2,}/',' ',$string);

		return $string;
	}
}