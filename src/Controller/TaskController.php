<?php

namespace App\Controller;

use App\Entity\Task;
use App\Enum\StatusEnum;
use App\Form\TaskForm;
use App\Security\Voter\TaskVoter;
use App\UseCase\TaskUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/update-status', name: 'update_status', methods: ['POST'])]
    public function updateStatus(Request $request): JsonResponse
    {
        if (!$this->isCsrfTokenValid('task-status', (string) $request->headers->get('X-CSRF-TOKEN'))) {
            return new JsonResponse(['error' => 'Jeton CSRF invalide'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Corps de requête invalide'], Response::HTTP_BAD_REQUEST);
        }

        $taskId = $data['id'] ?? null;
        $status = $data['status'] ?? null;

        if (!is_numeric($taskId) || !is_string($status)) {
            return new JsonResponse(['error' => 'Données manquantes'], Response::HTTP_BAD_REQUEST);
        }

        $newStatus = StatusEnum::tryFrom($status);

        if (!$newStatus) {
            return new JsonResponse(['error' => 'Statut inconnu'], Response::HTTP_BAD_REQUEST);
        }

        $task = $this->taskUseCase->find((int) $taskId);

        if (!$task) {
            return new JsonResponse(['error' => 'Tâche introuvable'], Response::HTTP_NOT_FOUND);
        }

        if (!$this->isGranted(TaskVoter::CHANGE_STATUS, $task)) {
            return new JsonResponse(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }

        $this->taskUseCase->changeStatus($task, $newStatus);

        return new JsonResponse(['success' => true, 'newStatus' => $newStatus->value]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ?Task $task): Response
    {
        if (!$task) {
            $task = new Task();
        } elseif (!$this->isGranted(TaskVoter::EDIT, $task)) {
            throw $this->createAccessDeniedException();
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

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted(TaskVoter::VIEW, subject: 'task')]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    #[IsGranted(TaskVoter::DELETE, subject: 'task')]
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $this->taskUseCase->remove($task);
        }

        return $this->redirectToRoute('app_task.index', [], Response::HTTP_SEE_OTHER);
    }
}
