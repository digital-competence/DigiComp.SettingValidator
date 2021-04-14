<?php

namespace Neos\Flow\Core\Migrations;

/**
 * Restructures all Validation.yaml to new format
 */
class Version20170603120900 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'DigiComp.SettingValidator-20170603120900';
    }

    public function up(): void
    {
        $this->processConfiguration(
            'Validation',
            function (array &$configuration) {
                foreach ($configuration as $validatorName => &$validators) {
                    // guard that protects configuration, which has already the new format:
                    if (isset($validators['properties']) || isset($validators['self'])) {
                        continue;
                    }
                    $newConfiguration = ['properties' => [], 'self' => []];

                    foreach ($validators as $key => $validator) {
                        if (!isset($validator['validator']) || !isset($validator['options'])) {
                            $this->showWarning(
                                'The Validation.yaml files contained no validator or options for validation: '
                                . '"' . $validatorName . '.' . $key . '". It was not migrated.'
                            );
                            continue;
                        }
                        if (isset($validator['property'])) {
                            $newConfiguration['properties'][$validator['property']][$validator['validator']] =
                                $validator['options'];
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
