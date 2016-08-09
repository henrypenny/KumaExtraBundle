<?php

namespace Hmp\KumaExtraBundle\Form;

use Symfony\Component\Form\ResolvedFormType as BaseResolvedFormType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\ButtonBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\Form\SubmitButtonBuilder;

/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 2/08/16
 * Time: 6:06 PM
 */
class ResolvedFormType extends BaseResolvedFormType
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $blockPrefix;

	/**
	 * @var FormTypeInterface
	 */
	private $innerType;

	/**
	 * @var FormTypeExtensionInterface[]
	 */
	private $typeExtensions;

	/**
	 * @var ResolvedFormTypeInterface|null
	 */
	private $parent;

	/**
	 * @var OptionsResolver
	 */
	private $optionsResolver;

	/**
	 * Creates a new builder instance.
	 *
	 * Override this method if you want to customize the builder class.
	 *
	 * @param string               $name      The name of the builder
	 * @param string               $dataClass The data class
	 * @param FormFactoryInterface $factory   The current form factory
	 * @param array                $options   The builder options
	 *
	 * @return FormBuilderInterface The new builder instance
	 */
	protected function newBuilder($name, $dataClass, FormFactoryInterface $factory, array $options)
	{
		if ($this->innerType instanceof ButtonTypeInterface) {
			return new ButtonBuilder($name, $options);
		}

		if ($this->innerType instanceof SubmitButtonTypeInterface) {
			return new SubmitButtonBuilder($name, $options);
		}

		return new FormBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
	}
}
