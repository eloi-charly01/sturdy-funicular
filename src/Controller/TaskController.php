<?php

namespace App\Controller;

use App\Entity\Task;
use App\Enum\StatusEnum;
use App\Form\TaskForm;
use App\UseCase\TaskUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]

#[Route('/task', name: 'app_task.')]
final class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskUseCase $taskUseCase
    ) {}
    #[Route(name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $this->taskUseCase->getAllTask($request),
            'statuses' => StatusEnum::cases(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ?Task $task): Response
    {
        if (!$task) {
            $task = new Task();
        }
        $form = $this->createForm(TaskForm::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskUseCase->createOrUpdate($task);

            return $this->redirectToRoute('app_task.index', [], Response::HTTP_SEE_OTHER);
        }
        $template = $task->getId() ? 'task/edit.html.twig' :  'task/new.html.twig';

        return $this->render($template, [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task.index', [], Response::HTTP_SEE_OTHER);
    }
}
