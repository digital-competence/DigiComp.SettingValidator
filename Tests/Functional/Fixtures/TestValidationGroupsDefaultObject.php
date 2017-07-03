<?php
namespace DigiComp\SettingValidator\Tests\Functional\Fixtures;

use Neos\Flow\Annotations as Flow;

class TestValidationGroupsDefaultObject
{
    /**
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", options={"name": "GroupValidatorDefault"})
     * @var bool
     */
    protected $shouldBeTrue = false;

    /**
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", options={"name": "GroupValidatorCustom"})
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
