<?php
namespace DigiComp\SettingValidator;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;

/**
 * @Flow\Scope("prototype")
 */
class Package extends BasePackage
{
    /**
     * @param Bootstrap $bootstrap
     */
    public function boot(Bootstrap $bootstrap)
    {
        parent::boot($bootstrap);

        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(ConfigurationManager::class, 'configurationManagerReady',
            function ($configurationManager) {
                /** @var ConfigurationManager $configurationManager */
                $configurationManager->registerConfigurationType(
                    'Validation',
                    ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT,
                    true
                );
            }
        );
    }
}
