<?php

namespace DigiComp\SettingValidator;

/*
 * This file is part of the DigiComp.SettingValidator package.
 *
 * (c) digital competence
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;

/**
 * Package base class of the DigiComp.SettingValidator package.
 */
class Package extends BasePackage
{
    public const CONFIGURATION_TYPE_VALIDATION = 'Validation';

    /**
     * @param Bootstrap $bootstrap
     */
    public function boot(Bootstrap $bootstrap)
    {
        parent::boot($bootstrap);

        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(
            ConfigurationManager::class,
            'configurationManagerReady',
            function (ConfigurationManager $configurationManager) {
                $configurationManager->registerConfigurationType(
                    self::CONFIGURATION_TYPE_VALIDATION,
                    ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT,
                    true
                );
            }
        );
    }
}
