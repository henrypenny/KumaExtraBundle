<?php

namespace Hmp\KumaExtraBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 9/02/17
 * Time: 2:29 PM
 */
class ExtraArgsTypeExtension extends AbstractTypeExtension
{
    /**
     * Extends the form type which all other types extend
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * Add the extra row_attr option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'extra_args' => [],
            'hide_label' => false
        ));
    }

    /**
     * Pass the set row_attr options to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['extra_args'] = $options['extra_args'];
        $view->vars['hide_label'] = $options['hide_label'];
    }
}