<?php
namespace DigiComp\SettingValidator\Tests\Functional\Fixtures;

use Neos\Flow\Annotations as Flow;

class TestValidationGroupsCustomObject
{
    /**
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", options={"name": "GroupValidatorDefault", "validationGroups"={"Custom"}})
     * @var bool
     */
    protected $shouldBeTrue = false;

    /**
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", options={"name": "GroupValidatorCustom", "validationGroups"={"Custom"}})
     * @var bool
     */
    protected $shouldBeFalse = true;

    /**
     * @return bool
     */
    public function isShouldBeTrue(): bool
    {
        return $this->shouldBeTrue;
    }

    /**
     * @return bool
     */
    public function isShouldBeFalse(): bool
    {
        return $this->shouldBeFalse;
    }
}
