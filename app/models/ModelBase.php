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

    public function calcNameAndTripcode($post_name)
    {
        if(preg_match("/(#|!)(.*)/", $post_name, $regs)){
            $cap = $regs[2];
            $cap_full = '#' . $regs[2];
            if (function_exists('mb_convert_encoding')) {
                $recoded_cap = mb_convert_encoding($cap, 'SJIS', 'UTF-8');
                if ($recoded_cap != '') {
                    $cap = $recoded_cap;
                }
            }
            if (strpos($post_name, '#') === false) {
                $cap_delimiter = '!';
            } elseif (strpos($post_name, '!') === false) {
                $cap_delimiter = '#';
            } else {
                $cap_delimiter = (strpos($post_name, '#') < strpos($post_name, '!')) ? '#' : '!';
            }
            if (preg_match("/(.*)(" . $cap_delimiter . ")(.*)/", $cap, $regs_secure)) {
                $cap = $regs_secure[1];
                $cap_secure = $regs_secure[3];
                $is_secure_trip = true;
            } else {
                $is_secure_trip = false;
            }
            $tripcode = '';
            if ($cap != '') {
                $cap = strtr($cap, "&amp;", "&");
                $cap = strtr($cap, "&#44;", ", ");
                $salt = substr($cap."H.", 1, 2);
                $salt = preg_replace("/[^\.-z]/", ".", $salt);
                $salt = strtr($salt, ":;<=>?@[\\]^_`", "ABCDEFGabcdef");
                $tripcode = substr(crypt($cap, $salt), -10);
            }
            if ($is_secure_trip) {
                if ($cap != '') {
                    $tripcode .= '!';
                }
                $secure_tripcode = md5($cap_secure);
                if (function_exists('base64_encode')) {
                    $secure_tripcode = base64_encode($secure_tripcode);
                }
                if (function_exists('str_rot13')) {
                    $secure_tripcode = str_rot13($secure_tripcode);
                }
                $secure_tripcode = substr($secure_tripcode, 2, 10);
                $tripcode .= '!' . $secure_tripcode;
            }
            $name = preg_replace("/(" . $cap_delimiter . ")(.*)/", "", $post_name);
            return array($name, $tripcode);
        }
        return $post_name;
    }
}