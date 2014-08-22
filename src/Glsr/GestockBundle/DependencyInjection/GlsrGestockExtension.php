<?php
/**
 * Configuration de l'injection des dépendences
 * 
 * @category   DependencyInjection
 * @package    Gestock
 * @subpackage DependencyInjection
 * @author     Quétier Laurent 
 * <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php
 * GNU Public License
 * @version    GIT: a4ebf65097963d6681b30fcd9d9a1697a4fcce40
 */

namespace Glsr\GestockBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see 
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GlsrGestockExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
    }
}
