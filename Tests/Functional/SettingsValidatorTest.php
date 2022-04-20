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
        $result = $this->objectManager->get(SettingsValidator::class)->validate(new TestObject());

        self::assertTrue($result->hasErrors());
        self::assertCount(1, $result->getFlattenedErrors());
        self::assertCount(1, $result->forProperty('shouldBeFalse')->getErrors());
    }

    /**
     * @test
     * @throws InvalidValidationConfigurationException
     * @throws InvalidValidationOptionsException
     * @throws NoSuchValidatorException
     */
    public function conjunctionValidationWorksAsExpected(): void
    {
        $result = $this->objectManager
            ->get(ValidatorResolver::class)
            ->getBaseValidatorConjunction(TestObject::class)
            ->validate(new TestObject());

        self::assertTrue($result->hasErrors());
        self::assertCount(1, $result->getFlattenedErrors());
    }

    /**
     * @test
     * @throws InvalidValidationOptionsException
     */
    public function defaultValidationGroupWorks(): void
    {
        $result = $this->objectManager
            ->get(SettingsValidator::class, ['validationGroups' => ['Default']])
            ->validate(new TestValidationGroupsDefaultObject());

        self::assertTrue($result->hasErrors(), 'No errors for validation group "Default"');
        self::assertCount(1, $result->getFlattenedErrors(), 'Got a non expected number of errors for group "Default"');
        self::assertCount(1, $result->forProperty('shouldBeTrue')->getErrors(), 'Got no error for property');
    }

    /**
     * @test
     * @throws InvalidValidationOptionsException
     */
    public function customValidationGroupWorks(): void
    {
        $result = $this->objectManager
            ->get(SettingsValidator::class, ['validationGroups' => ['Custom']])
            ->validate(new TestValidationGroupsCustomObject());

        self::assertTrue($result->hasErrors(), 'No errors for validation group "Custom"');
        self::assertCount(1, $result->getFlattenedErrors(), 'Got a non expected number of errors for group "Custom"');
        self::assertCount(1, $result->forProperty('shouldBeFalse')->getErrors(), 'Got no error for property');
    }
}
