<?php

namespace App\Security\Voter;

use App\Entity\Relic;
use App\Entity\User;
use App\Enum\RelicStatus;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RelicVoter extends Voter
{
    // Define constants for the different permissions
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // Only vote on Relic objects for the supported permissions
        return $subject instanceof Relic && in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        // If the user is anonymous, only allow access to approved relics
        if (!$user instanceof User) {
            return $attribute === self::VIEW && $subject->getStatus() === RelicStatus::APPROVED;
        }
        
        // Admins can do anything
        if ($user->isAdmin()) {
            return true;
        }
        
        /** @var Relic $relic */
        $relic = $subject;
        
        switch ($attribute) {
            case self::VIEW:
                return $this->canView($relic, $user);
            case self::EDIT:
                return $this->canEdit($relic, $user);
            case self::DELETE:
                return $this->canDelete($relic, $user);
        }
        
        return false;
    }
    
    private function canView(Relic $relic, User $user): bool
    {
        // Approved relics can be viewed by anyone
        if ($relic->getStatus() === RelicStatus::APPROVED) {
            return true;
        }
        
        // Creators can view their own relics
        return $relic->getCreator() && $relic->getCreator()->getId() === $user->getId();
    }
    
    private function canEdit(Relic $relic, User $user): bool
    {
        // Only admins can edit relics (already handled in voteOnAttribute)
        return false;
    }
    
    private function canDelete(Relic $relic, User $user): bool
    {
        // Only admins can delete relics (already handled in voteOnAttribute)
        return false;
    }
}