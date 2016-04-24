<?php

namespace Phalcon\Utils;

class Timeformat
{
	/*
	 * $datatime Переменная содержащая Сегодня/Вчера/Полную дату
	 * $postMinute Тупо дописывается после всего дерьма
	 */
	public static function generate($timestamp)
	{	
		$postDate = date( "d.m.Y", $timestamp );
		$postMinute = date( "H:i", $timestamp );
		
		if ($postDate == date('d.m.Y')) {
			// Если сегодня
			$datetime = 'Cегодня в ';
		} else if ($postDate == date('d.m.Y', strtotime('-1 day'))) {
			// Если вчера
			$datetime = 'Вчера в ';
		} else {
			// Иначе
			$fulldate = date( "j # Y в ", $timestamp );
			$mon = date("m", $timestamp );
			switch( $mon ) {
				case  1: { $mon='Января'; } break;
				case  2: { $mon='Февраля'; } break;
				case  3: { $mon='Марта'; } break;
				case  4: { $mon='Апреля'; } break;
				case  5: { $mon='Мая'; } break;
				case  6: { $mon='Июня'; } break;
				case  7: { $mon='Июля'; } break;
				case  8: { $mon='Августа'; } break;
				case  9: { $mon='Сентября'; } break;
				case 10: { $mon='Октября'; } break;
				case 11: { $mon='Ноября'; } break;
				case 12: { $mon='Декабря'; } break;
			}
			$fulldate = str_replace( '#', $mon, $fulldate );
			$datetime = $fulldate;
		}
		return $datetime.$postMinute;

	}
}