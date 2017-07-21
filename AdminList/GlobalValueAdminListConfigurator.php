<?php

namespace Hmp\KumaExtraBundle\AdminList;

use Doctrine\ORM\EntityManager;

use Hmp\KumaExtraBundle\Form\GlobalValueAdminType;
use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;
use Kunstmaan\AdminListBundle\AdminList\SortableInterface;

/**
 * The admin list configurator for GlobalValue
 */
class GlobalValueAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator {
    /**
     * @param EntityManager $em        The entity manager
     * @param AclHelper     $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new GlobalValueAdminType());
    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('name', 'Name', true);
        $this->addField('plainText', 'Plain text', true);
        $this->addField('richText', 'Rich text', true);
    }

    /**
     * Build filters for admin list
     */
    public function buildFilters()
    {
        $this->addFilter('name', new ORM\StringFilterType('name'), 'Name');
        $this->addFilter('plainText', new ORM\StringFilterType('plainText'), 'Plain text');
        $this->addFilter('richText', new ORM\StringFilterType('richText'), 'Rich text');
    }

    /**
     * Get bundle name
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'HmpKumaExtraBundle';
    }

    /**
     * Get entity name
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'GlobalValue';
    }


}
