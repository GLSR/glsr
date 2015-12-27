<?php

/**
 * Configuration de l'injection des dépendences.
 *
 * @author     Quétier Laurent
 * <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php
 * GNU Public License
 *
 * @version    0.1.0
 */
namespace Glsr\GestockBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates
 * and merges configuration from your app/config files.
 *
 * @category   DependencyInjection

 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 * #cookbook-bundles-extension-config-class
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('glsr_gestock');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
