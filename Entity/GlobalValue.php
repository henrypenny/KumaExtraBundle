<?php

namespace Hmp\KumaExtraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kunstmaan\AdminBundle\Entity\AbstractEntity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * GlobalValue
 *
 * @ORM\Table(name="hmp_kuma_globalvalues")
 * @ORM\Entity(repositoryClass="Hmp\KumaExtraBundle\Repository\GlobalValueRepository")
 * @UniqueEntity("name")
 */
class GlobalValue extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true))
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="plain_text", type="text", nullable=true)
     */
    private $plainText;

    /**
     * @var string
     *
     * @ORM\Column(name="rich_text", type="text", nullable=true)
     */
    private $richText;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return GlobalValue
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set plainText
     *
     * @param string $plainText
     *
     * @return GlobalValue
     */
    public function setPlainText($plainText)
    {
        $this->plainText = $plainText;

        return $this;
    }

    /**
     * Get plainText
     *
     * @return string
     */
    public function getPlainText()
    {
        return $this->plainText;
    }

    /**
     * Set richText
     *
     * @param string $richText
     *
     * @return GlobalValue
     */
    public function setRichText($richText)
    {
        $this->richText = $richText;

        return $this;
    }

    /**
     * Get richText
     *
     * @return string
     */
    public function getRichText()
    {
        return $this->richText;
    }

}