<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Word
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\WordRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Word
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=255)
     */
    //@Assert\Regex("/^[\w0-9- ]+$/u")
    private $word;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="synonym", type="string", length=255)
     */
    // @Assert\Regex("/^[\w0-9, \[\]]+$/u")
    private $synonym;

    /**
     * @var string
     *
     * @ORM\Column(name="explanation", type="string", length=255)
     */
    // @Assert\Regex("/^[\w0-9,.!?- \[\]]+$/u")
    private $explanation;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set word
     *
     * @param string $word
     * @return Word
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set explanation
     *
     * @param string $explanation
     * @return Word
     */
    public function setExplanation($explanation)
    {
        $this->explanation = $explanation;

        return $this;
    }

    /**
     * Get explanation
     *
     * @return string 
     */
    public function getExplanation()
    {
        return $this->explanation;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Word
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Word
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set synonym
     *
     * @param string $synonym
     * @return Word
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;

        return $this;
    }

    /**
     * Get synonym
     *
     * @return string 
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Word
     */
    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);

        return $this;
    }

    private function slugify($text, $separator = '-')
    {

        $text = strtolower($text);


        // Remove all none word characters
        //$text = preg_replace('/\W{Cyrillic}/', '-', $text);


        // More stripping. Replace spaces with dashes TODO Fix later
/*        $text = strtolower(preg_replace('/[^A-Za-z0-9\/]+{Cyrillic}/', $separator,
            preg_replace('/([a-z\d]{Cyrillic})([A-Z]){Cyrillic}/', '\1_\2',
                preg_replace('/([A-Z]+)([A-Z][a-z]){Cyrillic}/', '\1_\2',
                    preg_replace('/::/', '/', $text)))));*/

        //return trim($text, $separator);
        return str_replace(' ', $separator, $text);
    }

    /** @ORM\PrePersist */
    public function setSlugValue()
    {
        $this->slug = $this->slugify($this->word);
    }
}
