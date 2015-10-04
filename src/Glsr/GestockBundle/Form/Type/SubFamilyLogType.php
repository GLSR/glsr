<?php

/**
 * SubFamilyLogType Form properties.
 *
 * PHP Version 5
 *
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version    GIT: 66c30ad5658ae2ccc5f74e6258fa4716d852caf9
 *
 * @link       https://github.com/GLSR/glsr
 */
namespace Glsr\GestockBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SubFamilyLogType Form properties.
 *
 * @category   Form
 */
class SubFamilyLogType extends AbstractType
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
                array('label' => 'glsr.gestock.settings.diverse.subfamily')
            )
            ->add(
                'familylog',
                'entity',
                array(
                    'class' => 'GlsrGestockBundle:FamilyLog',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'label' => 'glsr.gestock.settings.diverse.family'
                )
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
                'data_class' => 'Glsr\GestockBundle\Entity\SubFamilyLog',
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
        return 'glsr_gestockbundle_subfamilylog';
    }
}
