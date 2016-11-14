<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 6/02/16
 * Time: 10:16 PM
 */

namespace Hmp\KumaExtraBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseTwigExtension extends \Twig_Extension
{
    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }
}
