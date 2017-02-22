<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 15/03/16
 * Time: 3:34 PM
 */

namespace Hmp\KumaExtraBundle\Helper;


use Doctrine\ORM\EntityManager;
use Kunstmaan\NodeBundle\Entity\AbstractPage;
use Kunstmaan\NodeBundle\Entity\Node;
use Kunstmaan\NodeBundle\Entity\NodeTranslation;
use Kunstmaan\NodeBundle\Entity\NodeVersion;

class PageItem
{
    /** @var EntityManager $em */
    protected $em;

    /** @var Node $node */
    protected $node;

    /** @var NodeTranslation $nodeTranslation */
    protected $nodeTranslation;

    /** @var NodeVersion $nodeVersion */
    protected $nodeVersion;

    /** @var AbstractPage $page */
    protected $page;

    protected $initialised = false;

    /**
     * PageItem constructor.
     * @param NodeTranslation $nodeTranslation
     * @param EntityManager $em
     */
    public function __construct($nodeTranslation, EntityManager $em)
    {
        $this->em = $em;

        if($nodeTranslation instanceof AbstractPage) {
            $nodeTranslation = $this->em->getRepository('KunstmaanNodeBundle:NodeTranslation')->getNodeTranslationFor($nodeTranslation);
        }

        $this->nodeTranslation = $nodeTranslation;
    }

    public function init()
    {
        if(!$this->initialised) {
            $this->node = $this->nodeTranslation->getNode();
            $this->nodeVersion = $this->nodeTranslation->getPublicNodeVersion();
            $this->page = $this->nodeVersion->getRef($this->em);
            $this->initialised = true;
        }
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return NodeTranslation
     */
    public function getNodeTranslation()
    {
        return $this->nodeTranslation;
    }

    /**
     * @return NodeTranslation
     */
    public function getNT()
    {
        return $this->getNodeTranslation();
    }

    /**
     * @return NodeVersion
     */
    public function getNodeVersion()
    {
        return $this->nodeVersion;
    }

    /**
     * @return AbstractPage
     */
    public function getPage()
    {
        return $this->page;
    }
}