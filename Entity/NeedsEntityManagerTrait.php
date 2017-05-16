<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 16/05/17
 * Time: 10:58 AM
 */

namespace Hmp\KumaExtraBundle\Entity;


use Doctrine\ORM\EntityManager;

trait NeedsEntityManagerTrait
{

    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     * @return self
     */
    function setEntityMananger(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return EntityManager
     */
    function getEntityMananger()
    {
        return $this->entityManager;
    }
}