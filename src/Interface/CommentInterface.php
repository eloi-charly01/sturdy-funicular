<?php

namespace App\Interface;

use App\Entity\Comment;

interface CommentInterface
{
    public function createComment(Comment $comment): void;

    public function deleteComment(Comment $comment): void;
}
