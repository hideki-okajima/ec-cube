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

namespace Eccube\EventListener;

use Eccube\Common\EccubeConfig;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DoctrineExtensionListener implements EventSubscriberInterface
{
    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    /**
     * DoctrineExtensionListener constructor.
     * @param TranslatableListener $translatableListener
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        TranslatableListener $translatableListener,
        EccubeConfig $eccubeConfig
    ) {
        $this->translatableListener = $translatableListener;
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // 環境変数の ECCUBE_LOCALE を設定する
        $this->translatableListener->setTranslatableLocale($this->eccubeConfig['locale']);
        $this->translatableListener->setPersistDefaultLocaleTranslation(true);
        $this->translatableListener->setTranslationFallback(true);
    }
}
