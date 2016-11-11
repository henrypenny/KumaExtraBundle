<?php

namespace Hmp\KumaExtraBundle\Form\ChoiceList;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;

/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 27/11/15
 * Time: 1:12 PM
 */
class PageChoiceList implements ChoiceListInterface
{
    /**
     * @var EntityRepository $er;
     */
    protected $er;

    public function setEntityRepository(EntityRepository $er)
    {
        $this->er = $er;
    }

    /**
     * Returns all selectable choices.
     *
     * @return array The selectable choices indexed by the corresponding values
     */
    public function getChoices()
    {
        echo '';
    }

    /**
     * Returns the values for the choices.
     *
     * The values are strings that do not contain duplicates.
     *
     * @return string[] The choice values
     */
    public function getValues()
    {
        // TODO: Implement getValues() method.
    }

    /**
     * Returns the values in the structure originally passed to the list.
     *
     * Contrary to {@link getValues()}, the result is indexed by the original
     * keys of the choices. If the original array contained nested arrays, these
     * nested arrays are represented here as well:
     *
     *     $form->add('field', 'choice', array(
     *         'choices' => array(
     *             'Decided' => array('Yes' => true, 'No' => false),
     *             'Undecided' => array('Maybe' => null),
     *         ),
     *     ));
     *
     * In this example, the result of this method is:
     *
     *     array(
     *         'Decided' => array('Yes' => '0', 'No' => '1'),
     *         'Undecided' => array('Maybe' => '2'),
     *     )
     *
     * @return string[] The choice values
     */
    public function getStructuredValues()
    {
        // TODO: Implement getStructuredValues() method.
    }

    /**
     * Returns the original keys of the choices.
     *
     * The original keys are the keys of the choice array that was passed in the
     * "choice" option of the choice type. Note that this array may contain
     * duplicates if the "choice" option contained choice groups:
     *
     *     $form->add('field', 'choice', array(
     *         'choices' => array(
     *             'Decided' => array(true, false),
     *             'Undecided' => array(null),
     *         ),
     *     ));
     *
     * In this example, the original key 0 appears twice, once for `true` and
     * once for `null`.
     *
     * @return int[]|string[] The original choice keys indexed by the
     *                        corresponding choice values
     */
    public function getOriginalKeys()
    {
        // TODO: Implement getOriginalKeys() method.
    }

    /**
     * Returns the choices corresponding to the given values.
     *
     * The choices are returned with the same keys and in the same order as the
     * corresponding values in the given array.
     *
     * @param string[] $values An array of choice values. Non-existing values in
     *                         this array are ignored
     *
     * @return array An array of choices
     */
    public function getChoicesForValues(array $values)
    {
        // TODO: Implement getChoicesForValues() method.
    }

    /**
     * Returns the values corresponding to the given choices.
     *
     * The values are returned with the same keys and in the same order as the
     * corresponding choices in the given array.
     *
     * @param array $choices An array of choices. Non-existing choices in this
     *                       array are ignored
     *
     * @return string[] An array of choice values
     */
    public function getValuesForChoices(array $choices)
    {
        // TODO: Implement getValuesForChoices() method.
    }
}