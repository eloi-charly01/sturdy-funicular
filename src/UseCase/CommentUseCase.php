<?php

namespace App\UseCase;

use App\Entity\Comment;
use App\Entity\Task;
use App\Interface\CommentInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class CommentUseCase
{

    public function __construct(
        private readonly CommentInterface $commentInterface,
        private readonly Security $security
    ) {}
    public function createComment(Task $task, string $content): void
    {

        $comment = (new Comment())
            ->setTask($task)
            ->setAuthor($this->security->getUser())
            ->setContent($content)
            ->setCreatedAt(new \DateTimeImmutable());
        $this->commentInterface->createComment($comment);
    }

    public function deleteComment(Comment $comment): void
    {
        $this->commentInterface->deleteComment($comment);
    }
}
