<?php


namespace App\Tests\Unit;


use App\Entity\Arduino;
use App\Entity\Consumption;
use App\Entity\ConsumptionMoment;
use App\Entity\Contact;
use App\Entity\User;
use App\Traits\IdTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class IdTraitTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @throws ReflectionException
     */
    public function testIfContactUseIdTraits()
    {
        $classes = [
            Arduino::class,
            Consumption::class,
            ConsumptionMoment::class,
            Contact::class,
            User::class
        ];

        $usingTraitQuantity = 0;

        foreach ($classes as $class) {
            in_array(
                IdTrait::class,
                array_keys((new ReflectionClass($class))->getTraits())
            ) === true ? $usingTraitQuantity++ : null;
        }

        self::assertSame(count($classes), $usingTraitQuantity);
    }
}