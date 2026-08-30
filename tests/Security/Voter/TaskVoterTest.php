<?php

namespace App\Tests\Security\Voter;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Security\Voter\TaskVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class TaskVoterTest extends TestCase
{
    private function voter(bool $isAdmin = false): TaskVoter
    {
        $security = $this->createStub(Security::class);
        $security->method('isGranted')->willReturn($isAdmin);

        return new TaskVoter($security);
    }

    private function token(?User $user): TokenInterface
    {
        $token = $this->createStub(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        return $token;
    }

    public function testAnonymousIsDenied(): void
    {
        $vote = $this->voter()->vote($this->token(null), new Task(), [TaskVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testStrangerCannotChangeStatus(): void
    {
        $task = (new Task())
            ->setResponsability(new User())
            ->setAssigned(new User());

        $vote = $this->voter()->vote($this->token(new User()), $task, [TaskVoter::CHANGE_STATUS]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testAssigneeCanChangeStatus(): void
    {
        $assignee = new User();
        $task = (new Task())->setAssigned($assignee);

        $vote = $this->voter()->vote($this->token($assignee), $task, [TaskVoter::CHANGE_STATUS]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $vote);
    }

    public function testAssigneeCannotDelete(): void
    {
        $assignee = new User();
        $task = (new Task())->setAssigned($assignee)->setResponsability(new User());

        $vote = $this->voter()->vote($this->token($assignee), $task, [TaskVoter::DELETE]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testResponsibleCanDelete(): void
    {
        $responsible = new User();
        $task = (new Task())->setResponsability($responsible);

        $vote = $this->voter()->vote($this->token($responsible), $task, [TaskVoter::DELETE]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $vote);
    }

    public function testProjectOwnerCanEditAnyTaskOfTheProject(): void
    {
        $owner = new User();
        $project = (new Project())->setOwner($owner);
        $task = (new Task())->setProject($project)->setResponsability(new User());

        $vote = $this->voter()->vote($this->token($owner), $task, [TaskVoter::EDIT]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $vote);
    }

    public function testTaskWithoutProjectDoesNotGrantOwnership(): void
    {
        $task = (new Task())->setResponsability(new User());

        $vote = $this->voter()->vote($this->token(new User()), $task, [TaskVoter::EDIT]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testAdminIsGrantedEverything(): void
    {
        $task = (new Task())->setResponsability(new User());

        $vote = $this->voter(isAdmin: true)->vote($this->token(new User()), $task, [TaskVoter::DELETE]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $vote);
    }

    public function testAbstainsOnUnsupportedSubject(): void
    {
        $vote = $this->voter()->vote($this->token(new User()), new Project(), [TaskVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $vote);
    }
}
