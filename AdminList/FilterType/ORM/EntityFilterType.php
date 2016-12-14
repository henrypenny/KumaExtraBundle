<?php

namespace Hmp\KumaExtraBundle\AdminList\FilterType\ORM;

use Doctrine\ORM\Query\Expr;
use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM\AbstractORMFilterType;
use Symfony\Component\HttpFoundation\Request;

/**
 * StringFilterType
 */
class EntityFilterType extends AbstractORMFilterType
{
    protected $property;
    protected $propertyAlias;

    /**
     * @param string $columnName The column name
     * @param string $alias      The alias
     */
    public function __construct($columnName, $alias, $property, $propertyAlias)
    {
        parent::__construct($columnName, $alias);
        $this->property = $property;
        $this->propertyAlias = $propertyAlias;
    }

    /**
     * @param Request $request  The request
     * @param array   &$data    The data
     * @param string  $uniqueId The unique identifier
     */
    public function bindRequest(Request $request, array &$data, $uniqueId)
    {
        $data['comparator'] = $request->query->get('filter_comparator_' . $uniqueId);
        $data['value']      = $request->query->get('filter_value_' . $uniqueId);
    }

    /**
     * @param array  $data     The data
     * @param string $uniqueId The unique identifier
     */
    public function apply(array $data, $uniqueId)
    {
        if (isset($data['value']) && isset($data['comparator'])) {

            $this->queryBuilder->leftJoin($this->alias . '.' . $this->columnName, $this->propertyAlias);

            $entity = $this->propertyAlias . '.' . $this->property;

            switch ($data['comparator']) {
                case 'equals':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->eq($entity, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('var_' . $uniqueId, $data['value']);
                    break;
                case 'notequals':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->neq($entity, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('var_' . $uniqueId, $data['value']);
                    break;
                case 'contains':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->like($entity, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('var_' . $uniqueId, '%' . $data['value'] . '%');
                    break;
                case 'doesnotcontain':
                    $this->queryBuilder->andWhere($entity . ' NOT LIKE :var_' . $uniqueId);
                    $this->queryBuilder->setParameter('var_' . $uniqueId, '%' . $data['value'] . '%');
                    break;
                case 'startswith':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->like($entity, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('var_' . $uniqueId, $data['value'] . '%');
                    break;
                case 'endswith':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->like($entity, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('var_' . $uniqueId, '%' . $data['value']);
                    break;
            }
        }
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'HmpKumaExtraBundle:AdminList\FilterType\ORM:entityFilter.html.twig';
    }
}
