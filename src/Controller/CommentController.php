<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Task;
use App\UseCase\CommentUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/comment', name: 'app_comment.')]
final class CommentController extends AbstractController
{

    public function __construct(private readonly CommentUseCase $commentUseCase) {}

    #[Route('/create/{task}', name: 'create', methods: ['POST'], requirements: ['task' => '\d+'])]
    public function create(Request $request, Task $task): Response
    {
        if (!$this->isCsrfTokenValid('comment' . $task->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $content = trim($request->request->getString('content'));

        if ($content !== '') {
            $this->commentUseCase->createComment($task, $content);
        }

        return $this->redirectToRoute('app_task.show', ['id' => $task->getId()]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Comment $comment): Response
    {
        if (!$this->isCsrfTokenValid('delete-comment' . $comment->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if ($comment->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $taskId = $comment->getTask()->getId();
        $this->commentUseCase->deleteComment($comment);

        return $this->redirectToRoute('app_task.show', ['id' => $taskId]);
    }
}
