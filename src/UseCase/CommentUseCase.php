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


    public function deleteComment(int $commentId): void
    {
        // Logic to delete a comment by its ID
        // This could involve interacting with a repository or service
        // to remove the comment from the database.

        // Example:
        // $this->commentRepository->delete($commentId);
    }

    public function getCommentsByTaskId(int $taskId): array
    {
        // Logic to fetch comments for a specific task by its ID
        // This could involve interacting with a repository or service
        // to retrieve comments from the database.

        // Example:
        // return $this->commentRepository->findBy(['taskId' => $taskId]);

        return []; // Placeholder return for example purposes
    }

    public function getAllComments(): array
    {


        return [];
    }

    public function updateComment(int $commentId, string $content): void
    {
        // Logic to update a comment's content by its ID
        // This could involve interacting with a repository or service
        // to update the comment in the database.

        // Example:
        // $comment = $this->commentRepository->find($commentId);
        // if ($comment) {
        //     $comment->setContent($content);
        //     $this->commentRepository->save($comment);
        // }
    }
}
