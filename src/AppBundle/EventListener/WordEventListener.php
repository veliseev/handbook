<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Word;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Router;

class WordEventListener
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Word) {
            $matches = array();
            preg_match_all('/\[([^\[]*)\]/', $entity->getSynonym(), $matches);

            unset($matches[0]);

            if (!count($matches[1])) {
                return;
            }

            foreach ($matches as $match) {
                $query = $entityManager->createQuery('SELECT w FROM AppBundle:Word w WHERE w.slug = :slug')
                    ->setParameter('slug', $match[0]);

                if ($word = $query->getOneOrNullResult()) {
                    $url = $this->router->generate('word_edit', array('slug' => $word->getSlug()));

                    $entity->setSynonym(
                        str_replace('[' . $match[0] . ']', '<a href="' . $url . '">[' . $match[0] . ']</a>', $entity->getSynonym())
                    );
                }
            }
        }
    }
}