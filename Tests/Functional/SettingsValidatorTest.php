<?php
namespace DigiComp\SettingValidator\Tests\Functional;

use DigiComp\SettingValidator\Tests\Functional\Fixtures\TestObject;
use DigiComp\SettingValidator\Validation\Validator\SettingsValidator;
use Neos\Flow\Tests\FunctionalTestCase;
use Neos\Flow\Validation\ValidatorResolver;

class SettingsValidatorTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function ifNoNameIsGivenClassNameIsUsed()
    {
        $validator = $this->objectManager->get(SettingsValidator::class);
        $result = $validator->validate(new TestObject());
        $this->assertTrue($result->hasErrors());
        $this->assertCount(1, $result->getFlattenedErrors());
        $this->assertCount(1, $result->forProperty('shouldBeFalse')->getErrors());
    }

    /**
     * @test
     */
    public function conjunctionValidationWorksAsExpected()
    {
        $validatorResolver = $this->objectManager->get(ValidatorResolver::class);
        $validator = $validatorResolver->getBaseValidatorConjunction(TestObject::class);
        $result = $validator->validate(new TestObject());
        $this->assertTrue($result->hasErrors());
        $this->assertCount(1, $result->getFlattenedErrors());
    }
}
