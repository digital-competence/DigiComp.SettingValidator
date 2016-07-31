<?php
namespace DigiComp\SettingValidator\Validation\Validator;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Reflection\ReflectionService;
use TYPO3\Flow\Validation\Exception\InvalidValidationConfigurationException;
use TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException;
use TYPO3\Flow\Validation\Validator\AbstractValidator;
use TYPO3\Flow\Validation\ValidatorResolver;

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
     * @var array
     */
    protected $validations;

    /**
     * @var \TYPO3\Flow\Configuration\ConfigurationManager
     */
    protected $configurationManager;

    public function injectConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->validations = $this->configurationManager->getConfiguration('Validation');
    }

    /**
     * @var ReflectionService
     * @Flow\Inject
     */
    protected $reflectionService;

    protected $supportedOptions = array(
        'name' => array('', 'Set the name of the setting-array to use', 'string', false)
    );

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
        $name = $this->options['name'] ? $this->options['name'] : $this->reflectionService->getClassNameByObject(
            $value
        );
        if (!isset($this->validations[$name])) {
            throw new InvalidValidationOptionsException(
                'The name ' . $name . ' has not been defined in Validation.yaml!',
                1397821438
            );
        }
        $config = &$this->validations[$name];
        foreach ($config as $validatorConfig) {
            $validator = $this->validatorResolver->createValidator(
                $validatorConfig['validator'],
                $validatorConfig['options']
            );
            if (!$validator) {
                throw new InvalidValidationConfigurationException(
                    'Validator could not be resolved: ' .
                        $validatorConfig['validator'] . '. Check your Validation.yaml',
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
}
