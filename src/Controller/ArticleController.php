<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\CommentArticle;
use App\Form\CommentArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentArticleRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles_index")
     */
    public function index(ArticleRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $repo->findAll();

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * Permet de créer une annonce
     * 
     * @Route("/article/new", name="article_new")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $article = new Article();

        $article->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        $article->setAuthor($this->getUser());
        
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush();
            
            return $this->redirectToRoute('article_show', [
                'slug' => $article->getSlug()
                ]);
                
                $this->addflash(
                    'success',
                    "Votre article a bien été ajouté !"
                );
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/article/{slug}/edit", name="article_edit")
     * @Security("is_granted('ROLE_USER') and user === article.getAuthor()")
     * 
     * @return Response
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ArticleType::class, $article);

        $article->setCreatedAt(new \DateTime('now'));

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'article <strong>{$article->getTitle()}</strong> a bien été enregistrée !"
            );
            
            return $this->redirectToRoute('article_show', [
                'slug' => $article->getSlug()
            ]);

        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
            ]);
    }

    /**
     * Permet d'afficher un seul article
     * 
     * @Route("/article/{slug}", name="article_show")
     *
     * @param CommentArticleRepository $repo
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $Manager
     * @return Response
     */
    public function show(CommentArticleRepository $repo, Article $article, Request $request, EntityManagerInterface $manager) {

        $commentArticles = $repo->findBy([
            'article' => $article,
            ],['createdAt' => 'desc']
        );

        $commentArticle = new CommentArticle();

        $form = $this->createForm(CommentArticleType::class, $commentArticle);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $commentArticle->setArticle($article)
                    ->setAuthor($this->getUser())
                    ->setArticle($article);

            $manager->persist($commentArticle);
            $manager->flush();

            $this->addflash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );
            return $this->redirect($request->getUri());
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentArticles' => $commentArticles,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un article
     * 
     * @Route("/article/{slug}/delete", name="article_delete")
     * @Security("is_granted('ROLE_USER') and user === article.getAuthor()")
     *  
     * @param Article $article
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Article $article, EntityManagerInterface $manager) {
        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'article <strong>{$article->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("security_account");
    }

}
