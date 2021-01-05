<?php

namespace App\Security;

use App\Entity\Consumption;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ConsumptionVoter
 */
class ConsumptionVoter extends Voter
{
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Consumption) {
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

        $subjectUser = $subject->getUser();

        if ($subjectUser !== $activeUser) {
            return false;
        }

        return true;
    }
}