<?php

namespace AppBundle\Listener;

class SluggableListener extends \Gedmo\Sluggable\SluggableListener
{
    public function __construct()
    {
        $this->setTransliterator(array('AppBundle\Util\Transliterator', 'transliterate'));
    }
}