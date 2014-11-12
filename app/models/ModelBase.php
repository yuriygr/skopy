<?php

class ModelBase extends \Phalcon\Mvc\Model
{
    public function formatDate($timestamp)
    {
        $fulldate = date( "j # Y @ H:i", $timestamp );
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
            $dayofweek = date("D", $timestamp );
            switch ($dayofweek) {
                case 'Sun': { $dayofweek = 'SOSкресение'; } break;
                case 'Mon': { $dayofweek = 'Понедельник'; } break;
                case 'Tue': { $dayofweek = 'Вторник'; } break;
                case 'Wed': { $dayofweek = 'Среда'; } break;
                case 'Thu': { $dayofweek = 'Четверг'; } break;
                case 'Fri': { $dayofweek = 'Девчатница'; } break;
                case 'Sat': { $dayofweek = 'Субкота'; } break;
            }
            $fulldate = str_replace( '#', $mon, $fulldate );
            $fulldate = str_replace( '%', $dayofweek, $fulldate );
            return $fulldate;
    }
}