<?php

namespace DigiComp\SettingValidator\Validation\Validator;

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
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Validator\AbstractValidator;
use Neos\Flow\Validation\ValidatorResolver;
use Neos\Utility\ObjectAccess;
use Neos\Utility\TypeHandling;

/**
 * Validator resolving other Validators defined in Validation.yaml
 */
class SettingsValidator extends AbstractValidator
{
    /**
     * @var ValidatorResolver
     * @Flow\Inject
     */
    protected $validatorResolver;

    /**
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * @var array
     */
    protected $supportedOptions = [
        'name' => ['', 'Set the name of the setting-array to use', 'string', false],
        'validationGroups' => [
            ['Default'],
            'Same as "Validation Groups" of Flow Framework. Defines the groups to execute.',
            'array',
            false
        ],
    ];

    /**
     * @var array
     */
    protected $validations;

    /**
     * @param ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->validations = $this->configurationManager->getConfiguration(Package::CONFIGURATION_TYPE_VALIDATION);
    }

    /**
     * Check if $value is valid. If it is not valid, needs to add an error
     * to Result.
     *
     * @param mixed $value
     * @throws InvalidValidationOptionsException
     * @throws InvalidValidationConfigurationException
     */
    protected function isValid($value)
    {
        $name = $this->options['name'] ? $this->options['name'] : TypeHandling::getTypeForValue($value);
        if (! isset($this->validations[$name])) {
            throw new InvalidValidationOptionsException(
                'The name ' . $name . ' has not been defined in Validation.yaml!',
                1397821438
            );
        }

        $config = $this->getConfigForName($name);

        foreach ($config as $validatorConfig) {
            if (! $this->doesValidationGroupsMatch($validatorConfig)) {
                continue;
            }

            $this->handleValidationGroups($validatorConfig);

            $validator = $this->validatorResolver->createValidator(
                $validatorConfig['validator'],
                $validatorConfig['options']
            );

            if (! $validator) {
                throw new InvalidValidationConfigurationException(
                    sprintf(
                        'Validator could not be resolved: "%s" Check your Validation.yaml',
                        $validatorConfig['validator']
                    ),
                    1402326139
                );
            }

            if (isset($validatorConfig['property'])) {
                $this->getResult()->forProperty($validatorConfig['property'])->merge(
                    $validator->validate(ObjectAccess::getPropertyPath($value, $validatorConfig['property']))
                );
            } else {
                $this->getResult()->merge($validator->validate($value));
            }
        }
    }

    /**
     * @param string $name
     * @return array
     */
    protected function getConfigForName($name): array
    {
        $config = [];
        if (isset($this->validations[$name]['self'])) {
            foreach ($this->validations[$name]['self'] as $validator => &$validation) {
                if (is_null($validation)) {
                    continue;
                }
                $newValidation['options'] = $validation;
                $newValidation['validator'] = $validator;
                $config[] = $newValidation;
            }
        }
        if (isset($this->validations[$name]['properties'])) {
            foreach ($this->validations[$name]['properties'] as $propertyName => &$validation) {
                foreach ($validation as $validator => &$options) {
                    if (is_null($options)) {
                        continue;
                    }
                    $newValidation['property'] = $propertyName;
                    $newValidation['validator'] = $validator;
                    $newValidation['options'] = $options;
                    $config[] = $newValidation;
                }
            }
        }
        return $config;
    }

    /**
     * Check whether at least one configured group does match, if any is configured.
     *
     * @param array $validatorConfig
     * @return bool
     */
    protected function doesValidationGroupsMatch(array &$validatorConfig)
    {
        if (isset($validatorConfig['options']['validationGroups']) && empty(array_intersect($validatorConfig['options']['validationGroups'], $this->options['validationGroups']))) {
            return false;
        }

        return true;
    }

    /**
     * Add validation groups for recursion if necessary.
     *
     * @param array $validatorConfig
     */
    protected function handleValidationGroups(array &$validatorConfig)
    {
        if ($validatorConfig['validator'] === 'DigiComp.SettingValidator:Settings') {
            $validatorConfig['options']['validationGroups'] = $this->options['validationGroups'];
        } elseif (isset($validatorConfig['options']['validationGroups'])) {
            unset($validatorConfig['options']['validationGroups']);
        }
    }
}
