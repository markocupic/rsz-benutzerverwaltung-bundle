<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT_KEY = 'rsz_benutzerverwaltung';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_KEY);

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('user_home_dir')->defaultValue('files/Dateiablage/user_dir')->end()
                ->arrayNode('sac_sections')
                    ->isRequired()->scalarPrototype()->end()
                ->end()
                ->arrayNode('leader_qualification')
                    ->isRequired()->scalarPrototype()->end()
                ->end()
                ->arrayNode('rsz_roles')
                    ->isRequired()->scalarPrototype()->end()
                ->end()
                ->arrayNode('wettkampfkategorien')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('age')
                                ->isRequired()->scalarPrototype()->end()
                            ->end()
                            ->enumNode('gender')
                                ->values(['female', 'male'])
                                ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
