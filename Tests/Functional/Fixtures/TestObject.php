<?php
namespace DigiComp\SettingValidator\Tests\Functional\Fixtures;

use Neos\Flow\Annotations as Flow;

class TestObject
{
    /**
     * @var bool
     */
    protected $shouldBeTrue = true;

    /**
     * @var bool
     */
    protected $shouldBeFalse = true;

    /**
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", options={"name": "TrueValidator"})
     * @var bool
     */
    protected $shouldBeTrueAndValidatedByAnnotation = false;

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

    /**
     * @return bool
     */
    public function isShouldBeTrueAndValidatedByAnnotation(): bool
    {
        return $this->shouldBeTrueAndValidatedByAnnotation;
    }
}
