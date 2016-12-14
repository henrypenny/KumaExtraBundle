<?php

namespace Hmp\KumaExtraBundle\Form\ChoiceList;

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterableQueryBuilderLoader extends ORMQueryBuilderLoader
{
    /**
     * Contains the query builder that builds the query for fetching the
     * entities.
     *
     * This property should only be accessed through queryBuilder.
     *
     * @var QueryBuilder
     */
    private $queryBuilder;


    private $filter;

    /**
     * Construct an ORM Query Builder Loader.
     *
     * @param QueryBuilder|\Closure $queryBuilder The query builder or a closure
     *                                            for creating the query builder.
     *                                            Passing a closure is
     *                                            deprecated and will not be
     *                                            supported anymore as of
     *                                            Symfony 3.0.
     * @param EntityManager         $manager      Deprecated.
     * @param string                $class        Deprecated.
     *
     *
     * @throws UnexpectedTypeException
     */
    public function __construct($queryBuilder, $manager = null, $class = null, $filter = null)
    {
        $filter = $queryBuilder['filter'];
        $queryBuilder = $queryBuilder['query_builder'];

        // If a query builder was passed, it must be a closure or QueryBuilder
        // instance
        if ($queryBuilder && !($queryBuilder instanceof QueryBuilder || $queryBuilder instanceof \Closure)) {
            throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder or \Closure');
        }

        if ($queryBuilder instanceof \Closure) {
            @trigger_error('Passing a QueryBuilder closure to '.__CLASS__.'::__construct() is deprecated since version 2.7 and will be removed in 3.0.', E_USER_DEPRECATED);

            if (!$manager instanceof EntityManager) {
                throw new UnexpectedTypeException($manager, 'Doctrine\ORM\EntityManager');
            }

            @trigger_error('Passing an EntityManager to '.__CLASS__.'::__construct() is deprecated since version 2.7 and will be removed in 3.0.', E_USER_DEPRECATED);
            @trigger_error('Passing a class to '.__CLASS__.'::__construct() is deprecated since version 2.7 and will be removed in 3.0.', E_USER_DEPRECATED);

            $queryBuilder = $queryBuilder($manager->getRepository($class));

            if (!$queryBuilder instanceof QueryBuilder) {
                throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder');
            }
        }

        $this->queryBuilder = $queryBuilder;
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntities()
    {
        if(!$this->queryBuilder) {
            throw new \Exception('query_builder option required.');
        }

        $results = $this->queryBuilder->getQuery()->execute();

        if(is_callable($this->filter)) {
            $results = call_user_func($this->filter, $results);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntitiesByIds($identifier, array $values)
    {
        $qb = clone ($this->queryBuilder);
        $alias = current($qb->getRootAliases());
        $parameter = 'ORMQueryBuilderLoader_getEntitiesByIds_'.$identifier;
        $where = $qb->expr()->in($alias.'.'.$identifier, ':'.$parameter);

        // Guess type
        $entity = current($qb->getRootEntities());
        $metadata = $qb->getEntityManager()->getClassMetadata($entity);
        if (in_array($metadata->getTypeOfField($identifier), array('integer', 'bigint', 'smallint'))) {
            $parameterType = Connection::PARAM_INT_ARRAY;

            // Filter out non-integer values (e.g. ""). If we don't, some
            // databases such as PostgreSQL fail.
            $values = array_values(array_filter($values, function ($v) {
                return (string) $v === (string) (int) $v;
            }));
        } else {
            $parameterType = Connection::PARAM_STR_ARRAY;
        }
        if (!$values) {
            return array();
        }

        return $qb->andWhere($where)
            ->getQuery()
            ->setParameter($parameter, $values, $parameterType)
            ->getResult();
    }
}
