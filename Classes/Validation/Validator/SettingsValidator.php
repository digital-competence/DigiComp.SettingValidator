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

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Exception\NoSuchValidatorException;
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
     * @Flow\Inject
     * @var ValidatorResolver
     */
    protected $validatorResolver;

    /**
     * @Flow\InjectConfiguration(type="Validation")
     * @var array
     */
    protected array $validations;

    /**
     * @inheritDoc
     */
    protected $supportedOptions = [
        'name' => ['', 'Name of the setting array to use', 'string'],
        'validationGroups' => [['Default'], 'Same as "Validation Groups" of Flow Framework', 'array'],
    ];

    /**
     * @inheritDoc
     * @throws InvalidValidationOptionsException
     * @throws InvalidValidationConfigurationException
     * @throws NoSuchValidatorException
     */
    protected function isValid($value): void
    {
        $validations = $this->validations;

        $name = $this->options['name'] !== '' ? $this->options['name'] : TypeHandling::getTypeForValue($value);
        if (!isset($validations[$name])) {
            throw new InvalidValidationOptionsException(
                'The name "' . $name . '" has not been defined in Validation.yaml!',
                1397821438
            );
        }

        foreach ($this->getConfigForValidation($validations[$name]) as $validatorConfig) {
            if (!$this->doesValidationGroupsMatch($validatorConfig)) {
                continue;
            }

            $this->handleValidationGroups($validatorConfig);

            $validator = $this->validatorResolver->createValidator(
                $validatorConfig['validator'],
                $validatorConfig['options']
            );

            if ($validator === null) {
                throw new InvalidValidationConfigurationException(
                    \sprintf(
                        'Validator "%s" could not be resolved. Check your Validation.yaml',
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
     * @param array $validation
     * @return array
     */
    protected function getConfigForValidation(array $validation): array
    {
        $config = [];

        if (isset($validation['self'])) {
            foreach ($validation['self'] as $validator => $options) {
                if ($options === null) {
                    continue;
                }
                $config[] = [
                    'validator' => $validator,
                    'options' => $options,
                ];
            }
        }

        if (isset($validation['properties'])) {
            foreach ($validation['properties'] as $property => $propertyValidation) {
                foreach ($propertyValidation as $validator => $options) {
                    if ($options === null) {
                        continue;
                    }
                    $config[] = [
                        'property' => $property,
                        'validator' => $validator,
                        'options' => $options,
                    ];
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
    protected function doesValidationGroupsMatch(array $validatorConfig): bool
    {
        return !isset($validatorConfig['options']['validationGroups'])
            || \array_intersect(
                $validatorConfig['options']['validationGroups'],
                $this->options['validationGroups']
            ) !== [];
    }

    /**
     * Add validation groups for recursion if necessary.
     *
     * @param array $validatorConfig
     */
    protected function handleValidationGroups(array &$validatorConfig): void
    {
        if ($validatorConfig['validator'] === 'DigiComp.SettingValidator:Settings') {
            $validatorConfig['options']['validationGroups'] = $this->options['validationGroups'];
        } elseif (isset($validatorConfig['options']['validationGroups'])) {
            unset($validatorConfig['options']['validationGroups']);
        }
    }
}
