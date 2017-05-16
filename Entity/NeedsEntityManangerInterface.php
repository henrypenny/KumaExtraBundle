<?php
namespace Hmp\KumaExtraBundle\Entity;
use Doctrine\ORM\EntityManager;

/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 16/05/17
 * Time: 10:57 AM
 */
interface NeedsEntityManangerInterface
{
    /**
     * @param EntityManager $entityManager
     * @return self
     */
    function setEntityMananger(EntityManager $entityManager);

    /**
     * @return EntityManager
     */
    function getEntityMananger();
}