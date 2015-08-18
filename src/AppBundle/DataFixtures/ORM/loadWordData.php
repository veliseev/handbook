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

class loadWordData  implements FixtureInterface {
    public function load(ObjectManager $manager)
    {
        $words = array(
            'бег' => array('synonyms'=>'спорт, кросс, марафон', 'explanation'=>'Движение куда-н., передвижение, при к-ром быстро и резко отталкиваются ногами от земли.'),
            'полет' => array('synonyms'=>'парение, планирование', 'explanation'=>'Движение, передвижение по воздуху.'));

        foreach($words as $key => $value){
            $word = new Word();
            $word->setWord($key);
            $word->setSynonym($value['synonyms']);
            $word->setExplanation($value['explanation']);
            $manager->persist($word);
        }

        $manager->flush();
    }
} 