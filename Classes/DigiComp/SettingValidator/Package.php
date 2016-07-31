<?php
namespace DigiComp\SettingValidator;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\Flow\Core\Bootstrap;
use \TYPO3\Flow\Package\Package as BasePackage;

/**
 * @Flow\Scope("prototype")
 */
class Package extends BasePackage
{

    public function boot(Bootstrap $bootstrap)
    {
        parent::boot($bootstrap);

        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(
            'TYPO3\Flow\Configuration\ConfigurationManager',
            'configurationManagerReady',
            function (ConfigurationManager $configurationManager) {
                $configurationManager->registerConfigurationType(
                    'Validation',
                    ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT,
                    true
                );
            }
        );
    }
}
