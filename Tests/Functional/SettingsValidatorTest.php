<?php

namespace DigiComp\SettingValidator\Tests\Functional;

use DigiComp\SettingValidator\Tests\Functional\Fixtures\TestObject;
use DigiComp\SettingValidator\Tests\Functional\Fixtures\TestValidationGroupsCustomObject;
use DigiComp\SettingValidator\Tests\Functional\Fixtures\TestValidationGroupsDefaultObject;
use DigiComp\SettingValidator\Validation\Validator\SettingsValidator;
use Neos\Flow\Tests\FunctionalTestCase;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Exception\NoSuchValidatorException;
use Neos\Flow\Validation\ValidatorResolver;

class SettingsValidatorTest extends FunctionalTestCase
{
    /**
     * @test
     * @throws InvalidValidationOptionsException
     */
    public function ifNoNameIsGivenClassNameIsUsed(): void
    {
        $validator = $this->objectManager->get(SettingsValidator::class);
        $result = $validator->validate(new TestObject());
        $this->assertTrue($result->hasErrors());
        $this->assertCount(1, $result->getFlattenedErrors());
        $this->assertCount(1, $result->forProperty('shouldBeFalse')->getErrors());
    }

    /**
     * @test
     * @throws InvalidValidationConfigurationException
     * @throws InvalidValidationOptionsException
     * @throws NoSuchValidatorException
     */
    public function conjunctionValidationWorksAsExpected(): void
    {
        $validatorResolver = $this->objectManager->get(ValidatorResolver::class);
        $validator = $validatorResolver->getBaseValidatorConjunction(TestObject::class);
        $result = $validator->validate(new TestObject());
        $this->assertTrue($result->hasErrors());
        $this->assertCount(1, $result->getFlattenedErrors());
    }

    /**
     * @test
     * @throws InvalidValidationOptionsException
     */
    public function defaultValidationGroupWorks(): void
    {
        $validator = $this->objectManager->get(SettingsValidator::class, ['validationGroups' => ['Default']]);
        $result = $validator->validate(new TestValidationGroupsDefaultObject());
        $this->assertTrue($result->hasErrors(), 'No Errors for validation group "Default"');
        $this->assertCount(1, $result->getFlattenedErrors(), 'Got a non expected number of errors for group "Default"');
        $this->assertCount(1, $result->forProperty('shouldBeTrue')->getErrors(), 'Got no error for property');
    }

    /**
     * @test
     * @throws InvalidValidationOptionsException
     */
    public function customValidationGroupWorks(): void
    {
        $validator = $this->objectManager->get(SettingsValidator::class, ['validationGroups' => ['Custom']]);
        $result = $validator->validate(new TestValidationGroupsCustomObject());
        $this->assertTrue($result->hasErrors(), 'No Errors for validation group "Custom"');
        $this->assertCount(1, $result->getFlattenedErrors(), 'Got a non expected number of errors for group "Custom"');
        $this->assertCount(1, $result->forProperty('shouldBeFalse')->getErrors(), 'Got no error for property');
    }
}
