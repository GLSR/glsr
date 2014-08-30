<?php

/**
 * UnitStorageType Form properties
 * 
 * PHP Version 5
 * 
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    GIT: e6aa22c616ccc10884c67779f7d35806ca4a8be8
 * @link       https://github.com/GLSR/glsr
 */

namespace Glsr\GestockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * UnitStorageType Form properties
 * 
 * @category   Form
 * @package    Gestock
 * @subpackage Settings
 */
class UnitStorageType extends AbstractType
{
    /**
     * buildForm
     * 
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     * 
     * @return Form                $form    Formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('abbr', 'text');
    }
    
    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The resolver for the options.
     * 
     * @return array DefaultOption
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Glsr\GestockBundle\Entity\UnitStorage'
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