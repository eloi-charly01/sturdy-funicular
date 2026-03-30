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
    #[Route('/index', name: 'index', methods: ['GET'])]
    public function index()
    {
        // This method will handle the display of comments
        // Logic to fetch and display comments goes here
    }

    #[Route('/create/{task}', name: 'create', methods: ['POST'], requirements: ['task' => '\d+'])]
    public function create(Request $request, Task $task): Response
    {
        $content = $request->request->get('content');
        $this->commentUseCase->createComment($task, $content);
        return $this->redirectToRoute('app_task.show', ['id' => $task->getId()]);
    }

    public function delete()
    {
        // This method will handle the deletion of a comment
        // Logic to delete a comment goes here
    }
}
