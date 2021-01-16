<?php

namespace App\Validator\Constraints;

use App\Entity\Arduino as ArduinoEntity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UserArduinoValidator
 *
 * @Annotation
 */
class UserArduinoValidator extends ConstraintValidator
{
    /**
     * @param ArduinoEntity $arduino
     * @param Constraint $constraint
     */
    public function validate($arduino, Constraint $constraint): void
    {
        if ($arduino->isActive() && $arduino->getUser()->getActiveArduino() !== null) {
            $this->context->buildViolation('Er mag maar 1 actieve arduino zijn per gebruiker.')
                ->atPath('arduino')
                ->addViolation();
        }
    }
}