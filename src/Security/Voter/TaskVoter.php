<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class TaskVoter extends Voter
{
    public const VIEW = 'TASK_VIEW';
    public const EDIT = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';
    public const CHANGE_STATUS = 'TASK_CHANGE_STATUS';

    public function __construct(private readonly Security $security) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CHANGE_STATUS], true)
            && $subject instanceof Task;
    }

    /**
     * @param Task $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $isResponsible = $subject->getResponsability() === $user;
        $isAssignee = $subject->getAssigned() === $user;
        $isProjectOwner = $subject->getProject()?->getOwner() === $user;

        return match ($attribute) {
            self::VIEW => true,
            self::EDIT, self::CHANGE_STATUS => $isResponsible || $isAssignee || $isProjectOwner,
            self::DELETE => $isResponsible || $isProjectOwner,
        };
    }
}
