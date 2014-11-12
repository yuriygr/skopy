<?php

namespace Phalcon\Utils;

class Slug
{
	public static function generate($string)
	{	
		// Удаляем пробелы с конца строки
		$string =  rtrim($string);
		// Оставляем только один пробел
		$string =  preg_replace('/\s{1,}/',' ',$string);
		// Заменяем пробелы разделителем
		$string = preg_replace('/\s/', '-', $string);
		// Переводим текст в нижний регистр, strtolower() с кириллицей применить не получится
		$string = mb_convert_case($string, MB_CASE_LOWER, 'UTF-8');

		# Транслитерация

		$alphabet = [
			"а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e",
			"ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m",
			"н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t",
			"у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sch",
			"ы" => "i", "э" => "e", "ю" => "u", "я" => "ya", "ь" => "", "ъ" => ""
		];

		$string = strtr($string, $alphabet);

		$string = preg_replace('/[^a-zа-яё0-9_-]/u', '', $string); # убираем ненужные символы

		return $string;

	}
}