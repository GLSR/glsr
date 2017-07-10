<?php
/**
 * TvaType Form properties.
 *
 * PHP Version 5
 *
 * @author    Quétier Laurent <lq@dev-int.net>
 * @copyright 2014 Dev-Int GLSR
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version GIT: <git_id>
 *
 * @link https://github.com/Dev-Int/glsr
 */
namespace AppBundle\Form\Type\Settings\Diverse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\EventListener\AddSaveEditFieldSubscriber;

use Symfony\Component\Form\Extension\Core\Type\PercentType;

/**
 * TvaType Form properties.
 *
 * @category Form
 */
class TvaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'rate',
                PercentType::class,
                ['scale' => 2, 'type' => 'fractional', 'label' => 'gestock.settings.diverse.vat',]
            )
            ->addEventSubscriber(new AddSaveEditFieldSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Settings\Diverse\Tva',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tva';
    }
}