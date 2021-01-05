<?php

namespace App\Security;

use App\Entity\ConsumptionMoment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ConsumptionMomentVoter
 */
class ConsumptionMomentVoter extends Voter
{
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof ConsumptionMoment) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $activeUser = $token->getUser();

        if (!$activeUser instanceof User) {
            return false;
        }

        if ($activeUser->getArduino() === null) {
            return false;
        }

        if ($attribute !== 'new') {
            $subjectUser = $subject->getUser();

            if ($subjectUser !== $activeUser) {
                return false;
            }
        }

        return true;
    }
}