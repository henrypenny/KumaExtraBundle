<?php
namespace Hmp\KumaExtraBundle\Form\Type;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 27/11/15
 * Time: 1:39 PM
 */
class ContainerAwareType extends AbstractType implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'container' => $this->container
        ));
    }

    public function getName()
    {
        return 'container_aware';
    }

    public function getParent() {
        return 'form';
    }
}
