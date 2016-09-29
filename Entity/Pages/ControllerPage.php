<?php

namespace Hmp\KumaExtraBundle\Entity\PageParts;

use Doctrine\ORM\Mapping as ORM;

use Kunstmaan\NodeBundle\Controller\SlugActionInterface;
use Kunstmaan\NodeBundle\Entity\AbstractPage;
use Kunstmaan\NodeSearchBundle\Helper\SearchTypeInterface;
use Kunstmaan\PagePartBundle\Helper\HasPageTemplateInterface;
use Kunstmaan\PagePartBundle\PagePartAdmin\PagePartAdminConfiguratorInterface;
use Kunstmaan\PagePartBundle\PageTemplate\PageTemplateInterface;

abstract class ControllerPage extends AbstractPage implements HasPageTemplateInterface, SearchTypeInterface, SlugActionInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="controller_action", type="text", nullable=true)
     */
    protected $controllerAction;

    public function getControllerAction()
    {
        return $this->controllerAction?$this->controllerAction:'KunstmaanNodeBundle:Slug:slug';
    }

    public function setControllerAction($controllerAction)
    {
        $this->controllerAction = $controllerAction;
    }
}
