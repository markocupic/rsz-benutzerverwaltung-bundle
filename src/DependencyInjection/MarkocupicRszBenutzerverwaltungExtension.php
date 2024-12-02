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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MarkocupicRszBenutzerverwaltungExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );

        $loader->load('services.yaml');

        $configuration = new Configuration();
        $rootKey = $this->getAlias();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter($rootKey.'.user_home_dir', $config['user_home_dir']);
        $container->setParameter($rootKey.'.sac_sections', $config['sac_sections']);
        $container->setParameter($rootKey.'.leader_qualification', $config['leader_qualification']);
        $container->setParameter($rootKey.'.rsz_roles', $config['rsz_roles']);
        $container->setParameter($rootKey.'.wettkampfkategorien', $config['wettkampfkategorien']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        // Default root key would be markocupic_rsz_benutzerverwaltung_bundle
        return Configuration::ROOT_KEY;
    }
}
