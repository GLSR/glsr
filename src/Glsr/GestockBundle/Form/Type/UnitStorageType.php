<?php

/**
 * UnitStorageType Form properties.
 *
 * PHP Version 5
 *
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version    0.1.0
 *
 * @link       https://github.com/Dev-Int/glsr
 */
namespace Glsr\GestockBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * UnitStorageType Form properties.
 *
 * @category   Form
 */
class UnitStorageType extends AbstractType
{
    /**
     * buildForm.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @return Form $form    Formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                ['label' => 'glsr.gestock.settings.diverse.unitstorage']
            )
            ->add(
                'abbr',
                'text',
                ['label' => 'glsr.gestock.settings.diverse.abbreviation']
            )
            ->add(
                'save',
                'submit',
                array(
                    'attr' => array(
                        'class' => 'btn btn-default btn-primary'
                    ),
                    'label' => 'glsr.gestock.settings.form.save'
                )
            )
            ->add(
                'addmore',
                'submit',
                array(
                    'attr' => array(
                        'class' => 'btn btn-default btn-primary'
                    ),
                    'label' => 'glsr.gestock.settings.form.save&more'
                )
            );
    }

    /**
     * Configure the default options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @return array DefaultOption
     */
    public function configureOptions(OptionsResolver$resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Glsr\GestockBundle\Entity\UnitStorage',
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'glsr_gestockbundle_unitstorage';
    }
}
