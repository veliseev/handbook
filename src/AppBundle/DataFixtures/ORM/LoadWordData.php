<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.08.15
 * Time: 15:50
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Word;

class LoadWordData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $words = array(
            'бег' => array('synonyms'=>'спорт, кросс, марафон', 'explanation'=>'Движение куда-н., передвижение, при к-ром быстро и резко отталкиваются ногами от земли.'),
            'полет' => array('synonyms'=>'парение, планирование', 'explanation'=>'Движение, передвижение по воздуху.'),
            'прыжки' => array('synonyms'=>'прыжок', 'explanation'=>'Отталкивание от земли в каком-либо направлении.'),
            'плавание' => array('synonyms'=>'спорт', 'explanation'=>'Передвижение по воде.'),
            'баскетбол' => array('synonyms'=>'спорт', 'explanation'=>'Командная игра с мячом.'),
            'спорт' => array('synonyms'=>'здоровье', 'explanation'=>'Физическая деятельность человека, направленная на поддержание и улучшение здоровья.'),
        );

        foreach ($words as $key => $value) {
            $word = new Word();
            $word->setWord($key);
            $word->setSlug($key);
            $word->setSynonym($value['synonyms']);
            $word->setExplanation($value['explanation']);
            $manager->persist($word);
        }

        $manager->flush();
    }
} 