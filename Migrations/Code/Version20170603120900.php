<?php
namespace Neos\Flow\Core\Migrations;

/*
 * This file is part of the DigiComp.SettingValidator package.
 *
 * (c) digital competence
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use DigiComp\SettingValidator\Package;

/**
 * Restructures Validation.yamls to new format
 */
class Version20170603120900 extends AbstractMigration
{
    public function getIdentifier()
    {
        return 'DigiComp.SettingValidator-20170603120900';
    }

    /**
     * @return void
     */
    public function up()
    {
        $this->processConfiguration(Package::CONFIGURATION_TYPE_VALIDATION,
            function (&$configuration) {
                foreach ($configuration as $validatorName => &$validators) {
                    $newConfiguration = ['properties' => [], 'self' => []];

                    foreach ($validators as $key => &$validator) {
                        if (!isset($validator['validator']) || !isset($validator['options'])) {
                            $this->showWarning('The Validation.yaml files contained no validator or options for ' .
                                'validation: "' . $validatorName . '.' . $key . '". It was not be migrated.');
                            continue;
                        }
                        if (isset($validator['property'])) {
                            $newConfiguration['properties'][$validator['property']][$validator['validator']]
                                = $validator['options'];
                        } else {
                            $newConfiguration['self'][$validator['validator']] = $validator['options'];
                        }
                    }
                    $validators = $newConfiguration;
                }
            },
            true
        );
    }
}
