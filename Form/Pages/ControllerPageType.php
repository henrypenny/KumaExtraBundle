<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 29/09/16
 * Time: 10:33 AM
 */

namespace Hmp\KumaExtraBundle\Form\Pages;

use Kunstmaan\NodeBundle\Form\PageAdminType;
use Symfony\Component\Form\FormBuilderInterface;

class ControllerPageType extends PageAdminType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @SuppressWarnings("unused")
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('controllerAction', 'text', array(
            'required' => false,
            'attr' => array('style' => 'font-family: monospace; font-weight: bold; color: #77ff77; background-color: black;'))

        );
    }
}
