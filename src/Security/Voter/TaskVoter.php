<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const EDIT = 'TASK_EDIT';
    public const VIEW = 'TASK_VIEW';
    public const DELETE = 'TASK_EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE], true)
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $subject->getUser()->getUserIdentifier() === $user->getUserIdentifier();
                break;
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return $subject->getUser()->getUserIdentifier() === $user->getUserIdentifier();
                break;
            case self::DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                return $subject->getUser()->getUserIdentifier() === $user->getUserIdentifier();
                break;
        }

        return false;
    }
}
