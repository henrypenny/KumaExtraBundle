<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 16/11/15
 * Time: 11:41 AM
 */

namespace Hmp\KumaExtraBundle\EventListener;

use Burgerfuel\CmsBundle\Entity\Pages\BaseArticleOverviewPage;
use Burgerfuel\CmsBundle\Entity\Pages\BaseArticlePage;
use Burgerfuel\CmsBundle\Entity\Pages\BasePage;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Hmp\KumaExtraBundle\Entity\NeedsEntityManangerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityLoadListener
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof NeedsEntityManangerInterface) {
            $entity->setEntityMananger($args->getEntityManager());
        }
    }
}