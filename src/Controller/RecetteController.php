<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Entity\CommentRecette;
use App\Form\CommentRecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CommentRecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    /**
     * @Route("/recettes", name="recettes_index")
     */
    public function index(RecetteRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $repo->findAll();

        $recettes = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes
        ]);
    }

    /**
     * Permet de créer une recette
     * 
     * @Route("/recette/new", name="recette_new")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $recette = new Recette();

        $recette->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        $recette->setAuthor($this->getUser());
        
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($recette);
            $manager->flush();
            
            return $this->redirectToRoute('recette_show', [
                'slug' => $recette->getSlug()
                ]);
                
                $this->addflash(
                    'success',
                    "Votre recette a bien été ajouté !"
                );
        }

        return $this->render('recette/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/recette/{slug}/edit", name="recette_edit")
     * @Security("is_granted('ROLE_USER') and user === recette.getAuthor()")
     * 
     * @return Response
     */
    public function edit(Recette $recette, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(RecetteType::class, $recette);

        $recette->setCreatedAt(new \DateTime('now'));

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                "La recette <strong>{$recette->getTitle()}</strong> a bien été enregistrée !"
            );
            
            return $this->redirectToRoute('recette_show', [
                'slug' => $recette->getSlug()
            ]);

        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form->createView()
            ]);
    }

    /**
     * Permet d'afficher une seul recette
     * 
     * @Route("/recette/{slug}", name="recette_show")
     *
     * @param CommentRecetteRepository $repo
     * @param Recette $recette
     * @param Request $request
     * @param EntityManagerInterface $Manager
     * @return Response
     */
    public function show(CommentRecetteRepository $repo, Recette $recette, Request $request, EntityManagerInterface $manager) {

        $commentRecettes = $repo->findBy([
            'recette' => $recette,
            ],['createdAt' => 'desc']
        );

        $commentRecette = new CommentRecette();

        $form = $this->createForm(CommentRecetteType::class, $commentRecette);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $commentRecette->setrecette($recette)
                    ->setAuthor($this->getUser())
                    ->setRecette($recette);

            $manager->persist($commentRecette);
            $manager->flush();

            $this->addflash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );
            return $this->redirect($request->getUri());
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'commentRecettes' => $commentRecettes,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une recette
     * 
     * @Route("/recette/{slug}/delete", name="recette_delete")
     * @Security("is_granted('ROLE_USER') and user === recette.getAuthor()")
     *  
     * @param recette $recette
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(recette $recette, EntityManagerInterface $manager) {
        $manager->remove($recette);
        $manager->flush();

        $this->addFlash(
            'success',
            "La recette <strong>{$recette->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("security_account");
    }
}
