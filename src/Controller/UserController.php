<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\UseCase\UserUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'app_user.')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserUseCase $userUseCase
    ) {}
    #[Route('/index', name: 'index')]
    public function index(Request $request): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userUseCase->getUsers($request),
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_user.index');
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
