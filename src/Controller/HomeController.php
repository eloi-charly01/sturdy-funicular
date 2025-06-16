<?php

namespace App\Controller;

use App\UseCase\ProjectUseCase;
use App\UseCase\UserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class HomeController extends AbstractController
{

    public function __construct(
        private readonly UserUseCase $userUseCase,
        private readonly ProjectUseCase $projectUseCase
    ) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'count_users' => $this->userUseCase->countUsers(),
            'count_projects' => $this->projectUseCase->countProjects(),
        ]);
    }
}
