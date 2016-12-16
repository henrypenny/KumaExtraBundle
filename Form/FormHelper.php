<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 17/11/15
 * Time: 12:23 PM
 */

namespace Hmp\KumaExtraBundle\Form;

use Hmp\KumaExtraBundle\Helper\NSHelper;
use Doctrine\ORM\EntityRepository;
use Kunstmaan\AdminBundle\Entity\AbstractEntity;
use Kunstmaan\MediaBundle\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class FormHelper
{
    /**
     * @var FormBuilderInterface
     */
    protected $builder;

    protected $options;

    public function __construct(FormBuilderInterface $builder, array $options = null)
    {
        $this->builder = $builder;
        $this->options = $options;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array|null $options
     * @return FormHelper
     */
    public static function create(FormBuilderInterface $builder, array $options = null)
    {
        $fb = new self($builder, $options);
        return $fb;
    }

    /**
     * @param $child
     * @param null $type
     * @param array $options
     * @return FormHelper
     */
    public function add($child, $type = null, array $options = array())
    {
        $this->builder->add($child, $type, $options);
        return $this;
    }

    public function remove($name)
    {
        $this->builder->remove($name);
        return $this;
    }

    /**
     * @param $name
     * @param array $choices
     * @return $this
     */
    public function addDropdown($name, $choices = array())
    {
        $this->builder->add($name, 'choice', array(
            'choices'  => $choices,
        ));
        return $this;
    }

    /**
     * @param string $fieldName
     * @param string $className
     * @param string | \Closure $label
     */
    public function addEntityDropdown($fieldName, $className, $label)
    {
        $_className = NSHelper::resolve($className);

        $this->builder->add($fieldName, 'entity', array(
            'class' => $_className,
            'expanded' => false,
            'multiple' => false,
            'property' => $label,
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('e');
                return $qb;
            },
            'required' => false
        ));

        return $this;
    }

    /**
     * @param string $fieldName
     * @param string $className
     * @param string | \Closure $label
     */
    public function addPageDropdown($fieldName, $className, $label, $options = [])
    {
        $_className = NSHelper::resolve($className);

        $options = array_merge(array(
            'class' => $_className,
            'expanded' => false,
            'multiple' => false,
            'property' => $label,
            'query_builder' => function(EntityRepository $er) {
                $qb = $er->createQueryBuilder('e');
                return $qb;
            },
            'filter' => function($choices) use ($label) {

                $choices = array_filter($choices, function($choice) use ($label) {
                    return $choice->isPublicPage();
                });
                // Need to ignore errors here due to bug in php.
                @usort($choices, function($a, $b) use ($label) {
                    $getter = 'get' . ucfirst($label);
                    return strcmp($a->$getter(), $b->$getter());
                });
                return $choices;
            },
            'required' => false
        ), $options);

        $this->builder->add($fieldName, 'filterable_entity', $options);

        return $this;
    }

    protected function prefix($prefix = null, $fieldName)
    {
        if(!$prefix) {
            return $fieldName;
        }
        else {
            $fieldName = sprintf('%s%s', $prefix, ucfirst($fieldName));
            return $fieldName;
        }
    }
}
