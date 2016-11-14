<?php

namespace Hmp\KumaExtraBundle\Form;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory as BaseResolvedFormTypeFactory;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 2/08/16
 * Time: 6:04 PM
 */
class ResolvedFormTypeFactory extends BaseResolvedFormTypeFactory
{
	/**
	 * {@inheritdoc}
	 */
	public function createResolvedType(FormTypeInterface $type, array $typeExtensions, ResolvedFormTypeInterface $parent = null)
	{
		return new ResolvedFormType($type, $typeExtensions, $parent);
	}
}
