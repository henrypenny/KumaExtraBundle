<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 11/11/16
 * Time: 8:53 AM
 */

namespace Hmp\KumaExtraBundle\Twig;


use Symfony\Bridge\Twig\AppVariable;

class FormTwigExtension extends BaseTwigExtension
{
    function getServiceName()
    {
        return 'formService';
    }

    public function getName()
    {
        return 'form_twig_extension';
    }

    public function getFilters()
    {
        return [
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array (
            'test_value' => new \Twig_Function_Method($this, 'testValue', ['needs_context' => true])
        );
    }

    public function testValue($context, $type=null)
    {
        /** @var AppVariable $app */
        $app = $context['app'];
        if($app->getRequest()->query->has('test')) {
            switch($type) {
                case 'email':
                    return 'test' . time() . '@test.com';
                case 'checkbox':
                    return ' checked ';
                default:
                    return 'test';
            }
        }

    }

}