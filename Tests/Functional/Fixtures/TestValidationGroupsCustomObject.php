<?php
namespace DigiComp\SettingValidator\Tests\Functional\Fixtures;

use Neos\Flow\Annotations as Flow;

class TestValidationGroupsCustomObject
{
    /**
     * @var bool
     */
    protected $shouldBeTrue = false;

    /**
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
