<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Word
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\WordRepository")
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
    private $word;

    /**
     * @var string
     *
     * @ORM\Column(name="explanation", type="string", length=255)
     */
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
     * @ORM\ManyToMany(targetEntity="Word", mappedBy="mySynonyms")
     **/
    private $synonymsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="synonymsWithMe", cascade={"persist"})
     * @ORM\JoinTable(name="synonyms",
     *      joinColumns={@ORM\JoinColumn(name="word_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="synonym_word_id", referencedColumnName="id")}
     *      )
     **/
    private $mySynonyms;


    public function __construct() {
        $this->synonymsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mySynonyms = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add synonymsWithMe
     *
     * @param \AppBundle\Entity\Word $synonymsWithMe
     * @return Word
     */
    public function addSynonymsWithMe(\AppBundle\Entity\Word $synonymsWithMe)
    {
        $this->synonymsWithMe[] = $synonymsWithMe;

        return $this;
    }

    /**
     * Remove synonymsWithMe
     *
     * @param \AppBundle\Entity\Word $synonymsWithMe
     */
    public function removeSynonymsWithMe(\AppBundle\Entity\Word $synonymsWithMe)
    {
        $this->synonymsWithMe->removeElement($synonymsWithMe);
    }

    /**
     * Get synonymsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSynonymsWithMe()
    {
        return $this->synonymsWithMe;
    }

    /**
     * Add mySynonyms
     *
     * @param \AppBundle\Entity\Word $mySynonyms
     * @return Word
     */
    public function addMySynonym(\AppBundle\Entity\Word $mySynonyms)
    {
        $this->mySynonyms[] = $mySynonyms->addSynonymsWithMe($this);

        return $this;
    }

    /**
     * Remove mySynonyms
     *
     * @param \AppBundle\Entity\Word $mySynonyms
     */
    public function removeMySynonym(\AppBundle\Entity\Word $mySynonyms)
    {
        $this->mySynonyms->removeElement($mySynonyms);
    }

    /**
     * Get mySynonyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMySynonyms()
    {
        return $this->mySynonyms;
    }
}
