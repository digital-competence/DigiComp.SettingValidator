<?php

namespace DigiComp\SettingValidator\Tests\Functional\Fixtures;

class TestValidationGroupsCustomObject
{
    /**
     * @var bool
     */
    protected bool $shouldBeTrue = false;

    /**
     * @var bool
     */
    protected bool $shouldBeFalse = true;

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
