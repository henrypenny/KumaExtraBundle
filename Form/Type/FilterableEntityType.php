<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 27/11/15
 * Time: 2:34 PM
 */

namespace Hmp\KumaExtraBundle\Form\Type;


use Hmp\KumaExtraBundle\Form\ChoiceList\FilterableQueryBuilderLoader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterableEntityType extends DoctrineType
{
    protected $filter = null;

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        // Invoke the query builder closure so that we can cache choice lists
        // for equal query builders
        $queryBuilderNormalizer = function (Options $options, $queryBuilder) {

            if($queryBuilder == null) {
                $queryBuilder = $options['em']->getRepository($options['class'])->createQueryBuilder('e');
            }
            else if(is_callable($queryBuilder)) {

                $repo = $options['em']->getRepository($options['class']);

                $queryBuilder = call_user_func($queryBuilder, $repo);

                if (!$queryBuilder instanceof QueryBuilder) {
                    throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder');
                }
            }

            // This is a hack to get around the lack of options for getLoader
            // This array is unpacked inside FilterableQueryBuilderLoader
            return array(
                'query_builder' => $queryBuilder,
                'filter' => $options['filter']
            );
        };

        $resolver->setNormalizer('query_builder', $queryBuilderNormalizer);
        $resolver->setAllowedTypes('query_builder', array('null', 'callable', 'Doctrine\ORM\QueryBuilder'));
    }

    /**
     * Return the default loader object.
     *
     * @param ObjectManager $manager
     * @param QueryBuilder $queryBuilder
     * @param string $class
     *
     * @return ORMQueryBuilderLoader
     */
    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {
        return new FilterableQueryBuilderLoader($queryBuilder, $manager, $class, $this->filter);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'filter' => null
        ));
        $this->configureOptions($resolver);
    }

    public function getName()
    {
        return 'filterable_entity';
    }

    public function getParent()
    {
        return 'entity';
    }
}
