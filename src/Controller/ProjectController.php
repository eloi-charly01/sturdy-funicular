<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectForm;
use App\UseCase\ProjectUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/project', name: 'app_project.')]
final class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectUseCase $projectUseCase
    ) {}
    #[Route(name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $this->projectUseCase->getProjects($request),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Route('/create-new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ?Project $project): Response
    {
        if (!$project) {
            $project = new Project();
        }
        $form = $this->createForm(ProjectForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectUseCase->createOrUpdate($project);
            return $this->redirectToRoute('app_project.index', [], Response::HTTP_SEE_OTHER);
        }

        $template = $project->getId() ? 'project/edit.html.twig' :  'project/new.html.twig';
        return $this->render($template, [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->getPayload()->getString('_token'))) {
            $this->projectUseCase->remove($project);
        }

        return $this->redirectToRoute('app_project.index', [], Response::HTTP_SEE_OTHER);
    }
}
