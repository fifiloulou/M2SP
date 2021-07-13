<?php

namespace App\Controller\Admin;

use App\Entity\Une;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Recette;
use App\Entity\CommentArticle;
use App\Entity\CommentRecette;
use App\Repository\UneRepository;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\RecetteRepository;
use App\Repository\CommentArticleRepository;
use App\Repository\CommentRecetteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    protected $articleRepository;
    protected $recetterepository;
    protected $commentArticleRepository;
    protected $uneRepository;
    protected $userRepository;

    public function __construct(ArticleRepository $articleRepository, RecetteRepository $recetteRepository, CommentArticleRepository $commentArticleRepository, CommentRecetteRepository $commentRecetteRepository, UneRepository $uneRepository, UserRepository $userRepository)
    {
       $this->articleRepository = $articleRepository;
       $this->recetteRepository = $recetteRepository;
       $this->commentArticleRepository = $commentArticleRepository;
       $this->commentRecetteRepository = $commentRecetteRepository;
       $this->uneRepository = $uneRepository;
       $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'countArticle' => $this->articleRepository->countArticle(),
            'countRecette' => $this->recetteRepository->countRecette(),
            'countCommentArticle' => $this->commentArticleRepository->countCommentArticle(),
            'countCommentRecette' => $this->commentRecetteRepository->countCommentRecette(),
            'countUne' => $this->uneRepository->countUne(),
            'countUser' => $this->userRepository->countUser()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MDSP2')
            ->setTitle('<img src="M2SP.png">')
            ->setFaviconPath('logo1_size.jpg');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Voir le site', 'fas fa-eye', 'app');
        yield MenuItem::section('Menu');
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::section('Liste');
        yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Recettes', 'fas fa-book', Recette::class);
        yield MenuItem::linkToCrud('News', 'fas fa-file-alt', Une::class);
        yield MenuItem::linkToCrud('Commentaires des articles', 'fas fa-comments', CommentArticle::class);
        yield MenuItem::linkToCrud('Commentaires des recettes', 'fas fa-comments', CommentRecette::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);

    }
}
