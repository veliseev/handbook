<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Word;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Routing\Router;

class WordEventSubscriber implements EventSubscriber
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->makeCrossLinks($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->makeCrossLinks($args);
    }

    private function makeCrossLinks(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Word) {
            $matchesFromSynonym = array();
            $matchesFromExplanation = array();

            // Get words in brackets...
            preg_match_all('/\[([^\[]+)\]/', $entity->getSynonym(), $matchesFromSynonym);
            preg_match_all('/\[([^\[]+)\]/', $entity->getExplanation(), $matchesFromExplanation);

            // ... and wrap them in links.
            if (count($matchesFromSynonym[1])) {
                foreach ($matchesFromSynonym[1] as $match) {
                    $query = $entityManager->createQuery('SELECT w FROM AppBundle:Word w WHERE w.slug = :slug')
                        ->setParameter('slug', $match);

                    if ($word = $query->getOneOrNullResult()) {
                        $url = $this->router->generate('word_show', array('slug' => $word->getSlug()));

                        $entity->setSynonym(
                            str_replace('[' . $match . ']', '<a href="' . $url . '">[' . $match . ']</a>', $entity->getSynonym())
                        );
                    }
                }
            }

            if (count($matchesFromExplanation[1])) {
                foreach ($matchesFromExplanation[1] as $match) {
                    $query = $entityManager->createQuery('SELECT w FROM AppBundle:Word w WHERE w.slug = :slug')
                        ->setParameter('slug', $match);

                    if ($word = $query->getOneOrNullResult()) {
                        $url = $this->router->generate('word_show', array('slug' => $word->getSlug()));

                        $entity->setExplanation(
                            str_replace('[' . $match . ']', '<a href="' . $url . '">[' . $match . ']</a>', $entity->getExplanation())
                        );
                    }
                }
            }
        }
    }
}