<?php

namespace AppBundle\Util;

use Gedmo\Sluggable\Util\Urlizer;

class Transliterator extends \Behat\Transliterator\Transliterator
{
    public static function transliterate($text, $separator = '-')
    {

        //$text = my_transliteration_function($text);
        //var_dump(Urlizer::urlize($text)); die();
        return $text;
    }
}