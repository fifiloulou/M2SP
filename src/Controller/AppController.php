<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\RecetteRepository;
use App\Repository\UneRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository, UneRepository $uneRepository, RecetteRepository $recetteRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'articles' => $articleRepository->findBy([], ['id' => 'DESC'], 3),
            'users' => $userRepository->findBy([], ['id' => 'DESC'], 1),
            'unes' => $uneRepository->findBy([], ['id' => 'DESC'], 3),
            'recettes' => $recetteRepository->findBy([], ['id' => 'DESC'], 3),
        ]);
    }

    /**
     * @Route("/apropos", name="apropos")
     */
    public function apropos(): Response
    {
        return $this->render('app/apropos.html.twig', [
            'controller_name' => 'AproposController',
        ]);
    }

    /**
     * @Route("/mentionslegales", name="mentionslegales")
     */
    public function mentionslegales(): Response
    {
        return $this->render('app/mentionslegales.html.twig', [
            'controller_name' => 'AproposController',
        ]);
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu(): Response
    {
        return $this->render('app/cgu.html.twig', [
            'controller_name' => 'AproposController',
        ]);
    }
}
