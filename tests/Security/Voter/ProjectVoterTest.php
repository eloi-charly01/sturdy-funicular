<?php

namespace App\Tests\Security\Voter;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Security\Voter\ProjectVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class ProjectVoterTest extends TestCase
{
    private function voter(bool $isAdmin = false): ProjectVoter
    {
        $security = $this->createStub(Security::class);
        $security->method('isGranted')->willReturn($isAdmin);

        return new ProjectVoter($security);
    }

    private function token(?User $user): TokenInterface
    {
        $token = $this->createStub(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        return $token;
    }

    public function testAnonymousIsDenied(): void
    {
        $vote = $this->voter()->vote($this->token(null), new Project(), [ProjectVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testAnyAuthenticatedUserCanView(): void
    {
        $project = (new Project())->setOwner(new User());

        $vote = $this->voter()->vote($this->token(new User()), $project, [ProjectVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $vote);
    }

    public function testNonOwnerCannotEdit(): void
    {
        $project = (new Project())->setOwner(new User());

        $vote = $this->voter()->vote($this->token(new User()), $project, [ProjectVoter::EDIT]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testNonOwnerCannotDelete(): void
    {
        $project = (new Project())->setOwner(new User());

        $vote = $this->voter()->vote($this->token(new User()), $project, [ProjectVoter::DELETE]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testOwnerCanEdit(): void
    {
        $owner = new User();
        $project = (new Project())->setOwner($owner);

        $vote = $this->voter()->vote($this->token($owner), $project, [ProjectVoter::EDIT]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $vote);
    }

    public function testOwnerlessProjectIsNotEditableByAnyone(): void
    {
        $vote = $this->voter()->vote($this->token(new User()), new Project(), [ProjectVoter::EDIT]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $vote);
    }

    public function testAdminCanDelete(): void
    {
        $project = (new Project())->setOwner(new User());

        $vote = $this->voter(isAdmin: true)->vote($this->token(new User()), $project, [ProjectVoter::DELETE]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $vote);
    }

    public function testAbstainsOnUnsupportedSubject(): void
    {
        $vote = $this->voter()->vote($this->token(new User()), new Task(), [ProjectVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $vote);
    }
}
