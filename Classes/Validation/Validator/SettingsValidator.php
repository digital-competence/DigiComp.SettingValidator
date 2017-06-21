<?php
namespace DigiComp\SettingValidator\Validation\Validator;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Validator\AbstractValidator;
use Neos\Flow\Validation\ValidatorResolver;
use Neos\Utility\ObjectAccess;
use Neos\Utility\TypeHandling;

/**
 * Validator resolving other Validators defined in Validation.yaml
 *
 * @Flow\Scope("prototype")
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
     * @var ReflectionService
     * @Flow\Inject
     */
    protected $reflectionService;

    /**
     * @var array
     */
    protected $supportedOptions = [
        'name' => ['', 'Set the name of the setting-array to use', 'string', false],
        'validationGroups' => [['Default'], 'Same as "Validation Groups" of Flow Framework.', 'array', false],
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
        $this->validations = $this->configurationManager->getConfiguration('Validation');
    }

    /**
     * Check if $value is valid. If it is not valid, needs to add an error
     * to Result.
     *
     * @param mixed $value
     *
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

        $config = &$this->validations[$name];
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
                    'Validator could not be resolved: ' . $validatorConfig['validator'] . '. Check your Validation.yaml',
                    1402326139
                );
            }

            if (isset($validatorConfig['property'])) {
                $this->result->forProperty($validatorConfig['property'])->merge(
                    $validator->validate(ObjectAccess::getPropertyPath($value, $validatorConfig['property']))
                );
            } else {
                $this->result->merge($validator->validate($value));
            }
        }
    }

    /**
     * Check whether at least one configured group does match, if any is configured.
     *
     * @param array $validatorConfig
     * @return bool
     */
    protected function doesValidationGroupsMatch(array &$validatorConfig)
    {
        if (isset($validatorConfig['validationGroups'])
            && count(array_intersect($validatorConfig['validationGroups'], $this->options['validationGroups'])) === 0
        ) {
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
        if ($validatorConfig['validator'] === 'DigiComp.SettingValidator:Settings' && empty($validatorConfig['options']['validationGroups'])) {
            $validatorConfig['options']['validationGroups'] = $this->options['validationGroups'];
        }
    }
}
