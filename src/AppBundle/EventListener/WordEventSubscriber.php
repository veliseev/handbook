<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Word;
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

        $matchesFromSynonym = array();
        $matchesFromExplanation = array();
        // Get words in brackets.
        preg_match_all('/\[([^\[]+)\]/', $entity->getSynonym(), $matchesFromSynonym);
        preg_match_all('/\[([^\[]+)\]/', $entity->getExplanation(), $matchesFromExplanation);

        unset($matchesFromSynonym[0], $matchesFromExplanation[0]);

        if (!count($matchesFromSynonym[1]) && !count($matchesFromExplanation[1])) {
            return;
        }

        foreach ($matchesFromSynonym as $match) {
            $query = $entityManager->createQuery('SELECT w FROM AppBundle:Word w WHERE w.slug = :slug')
                ->setParameter('slug', $match[0]);

            if ($word = $query->getOneOrNullResult()) {
                $url = $this->router->generate('word_edit', array('slug' => $word->getSlug()));

                $entity->setSynonym(
                    str_replace('[' . $match[0] . ']', '<a href="' . $url . '">[' . $match[0] . ']</a>', $entity->getSynonym())
                );
            }
        }

        foreach ($matchesFromExplanation as $match) {
            $query = $entityManager->createQuery('SELECT w FROM AppBundle:Word w WHERE w.slug = :slug')
                ->setParameter('slug', $match[0]);

            if ($word = $query->getOneOrNullResult()) {
                $url = $this->router->generate('word_edit', array('slug' => $word->getSlug()));

                $entity->setExplanation(
                    str_replace('[' . $match[0] . ']', '<a href="' . $url . '">[' . $match[0] . ']</a>', $entity->getExplanation())
                );
            }
        }
    }
}