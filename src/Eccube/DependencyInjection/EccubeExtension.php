<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Configuration as DoctrineBundleConfiguration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EccubeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {
        // FrameworkBundleの設定を動的に変更する.
        $this->configureFramework($container);

        // プラグインの有効無効判定および初期化を行う.
        $this->configurePlugins($container);
    }

    protected function configureFramework(ContainerBuilder $container)
    {
        $forceSSL = $container->resolveEnvPlaceholders('%env(ECCUBE_FORCE_SSL)%', true);
        // envから取得した内容が文字列のため, booleanに変換
        if ('true' === $forceSSL) {
            $forceSSL = true;
        } elseif ('false' === $forceSSL) {
            $forceSSL = false;
        }

        // SSL強制時は, httpsのみにアクセス制限する
        $accessControl = [
          ['path' => '^/%eccube_admin_route%/login', 'roles' => 'IS_AUTHENTICATED_ANONYMOUSLY'],
          ['path' => '^/%eccube_admin_route%/', 'roles' => 'ROLE_ADMIN'],
          ['path' => '^/mypage/login', 'roles' => 'IS_AUTHENTICATED_ANONYMOUSLY'],
          ['path' => '^/mypage/withdraw_complete', 'roles' => 'IS_AUTHENTICATED_ANONYMOUSLY'],
          ['path' => '^/mypage/change', 'roles' => 'IS_AUTHENTICATED_FULLY'],
          ['path' => '^/mypage/', 'roles' => 'ROLE_USER'],
        ];
        if ($forceSSL) {
            foreach ($accessControl as &$control) {
                $control['requires_channel'] = 'https';
            }
        }

        // security.ymlでは制御できないため, ここで定義する.
        $container->prependExtensionConfig('security', [
          'access_control' => $accessControl,
        ]);
    }

    protected function configurePlugins(ContainerBuilder $container)
    {
        $enabled = $container->getParameter('eccube.plugins.enabled');

        $pluginDir = $container->getParameter('kernel.project_dir').'/app/Plugin';
        $this->configureTwigPaths($container, $enabled, $pluginDir);
        $this->configureTranslations($container, $enabled, $pluginDir);
    }

    /**
     * @param string $pluginDir
     */
    protected function configureTwigPaths(ContainerBuilder $container, $enabled, $pluginDir)
    {
        $paths = [];
        $projectDir = $container->getParameter('kernel.project_dir');

        foreach ($enabled as $code) {
            // app/template/plugin/[plugin code]
            $dir = $projectDir.'/app/template/plugin/'.$code;
            if (file_exists($dir)) {
                $paths[$dir] = $code;
            }
            // app/Plugin/[plugin code]/Resource/template
            $dir = $pluginDir.'/'.$code.'/Resource/template';
            if (file_exists($dir)) {
                $paths[$dir] = $code;
            }
        }

        if (!empty($paths)) {
            $container->prependExtensionConfig('twig', [
                'paths' => $paths,
            ]);
        }
    }

    /**
     * @param string $pluginDir
     */
    protected function configureTranslations(ContainerBuilder $container, $enabled, $pluginDir)
    {
        $paths = [];

        foreach ($enabled as $code) {
            $dir = $pluginDir.'/'.$code.'/Resource/locale';
            if (file_exists($dir)) {
                $paths[] = $dir;
            }
        }

        if (!empty($paths)) {
            $container->prependExtensionConfig('framework', [
                'translator' => [
                    'paths' => $paths,
                ],
            ]);
        }
    }
}
