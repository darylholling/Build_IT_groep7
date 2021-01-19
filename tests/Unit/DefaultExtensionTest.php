<?php

namespace App\Tests\Unit;

use App\Manager\ConsumptionManager;
use App\Twig\DefaultExtension;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class DefaultExtensionTest
 */
class DefaultExtensionTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var ConsumptionManager
     */
    private $defaultExtension;

    public function setUp(): void
    {
        self::bootKernel();

        $container = self::$container;

        $this->defaultExtension = $container->get(DefaultExtension::class);
    }

    public function testFormatBooleanTrueReturnsCorrectValue()
    {
        $readableBoolean = $this->defaultExtension->formatBoolean(true);

        $this->assertEquals('Ja', $readableBoolean);
    }

    public function testFormatBooleanFalseReturnsCorrectValue()
    {
        $readableBoolean = $this->defaultExtension->formatBoolean(false);

        $this->assertEquals('Nee', $readableBoolean);
    }

    public function testFormatBooleanReturnsString()
    {
        $readableBoolean = $this->defaultExtension->formatBoolean(false);

        $this->assertTrue(is_string($readableBoolean));
    }
}