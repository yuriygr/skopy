<?php

namespace Phalcon;

class Wakabamark
{
    private function MakeURL($string)
    {
        $string = preg_replace('#(http://|https://|ftp://)([^(\s<|\[)]*)#', '<a target="_blank" href="\\1\\2">\\1\\2</a>', $string);
        return $string;
    }

    private function BBCode($string)
    {
        $patterns = array(
            '`\[b\](.+?)\[/b\]`is',
            '`\[i\](.+?)\[/i\]`is',
            '`\[s\](.+?)\[/s\]`is',
            '`\[code\](.+?)\[/code\]`is',
            '`\[spoiler\](.+?)\[/spoiler\]`is',

            '`\*\*(.+?)\*\*`is',
            '`\*(.+?)\*`is',
            '`\~\~(.+?)\~\~`is',
            '`\%\%(.+?)\%\%`is',
            '`\`\`(.+?)\`\``is',

            '`--`is',
        );
        $replaces =  array(
            '<b>\\1</b>',
            '<i>\\1</i>',
            '<del>\\1</del>',
            '<pre><code>\\1</code></pre>',
            '<span class="spoiler">\\1</span>',

            '<b>\\1</b>',
            '<i>\\1</i>',
            '<del>\\1</del>',
            '<pre><code>\\1</code></pre>',
            '<span class="spoiler">\\1</span>',
            '—',
        );
        $string = preg_replace($patterns, $replaces , $string);
        return $string;
    }

    private function MakeLinks($strings)
    {
        $strings = preg_replace_callback('/&gt;&gt;([0-9,\-,\,]+)/', array(&$this, 'postRef'), $strings);
        return $strings;
    }

    private function MakeQuote($strings)
    {
        $strings = preg_replace('/^(&gt;[^>](.*))/m', '<span class="quote">$0</span>', $strings);
        return $strings;
    }

    function postRef($strings)
    {
        $id = &$strings[1];
        //$comments = Comments::findFirst($id);
        //if($comments->post) return '<a href="post/show/'.$comments->post.'#'.$id.'">>>'.$id.'</a>';
        //else return ">>$id";
        return ">>$id";
    }

    function text($string)
    {
        $string = trim($string);
        $string = htmlspecialchars($string, ENT_QUOTES);
        $string = $this->MakeURL($string); #Делаем УРЛЫ
        $string = $this->MakeLinks($string); #Делаем >>
        $string = $this->MakeQuote($string); #Делаем >
        $string = $this->BBCode($string); #ББшки
        $string = str_replace("\n", '<br />', $string);
        $string = preg_replace('#(<br(?: \/)?>\s*){3,}#i', '<br /><br />', $string);

        return $string;
    }
}