<?php

namespace App\Interface;

use App\Entity\Comment;

interface CommentInterface
{
    public function createComment(Comment $comment): void;

    // public function deleteComment(int $commentId): void;

    // public function getCommentsByTaskId(int $taskId): array;

    // public function getAllComments(): array;

    // public function updateComment(int $commentId, string $content): void;
}
