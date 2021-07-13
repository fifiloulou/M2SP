<?php

namespace App\Controller;

use App\Entity\Une;
use App\Form\UneType;
use App\Repository\UneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class UneController extends AbstractController
{
    /**
     * @Route("/unes", name="unes_index")
     */
    public function index(UneRepository $repo): Response
    {
        $events = $repo->findAll();
        $rdvs = [];
        foreach($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'url' =>$event->getUrl(),
                'title' => $event->getTitle(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('une/index.html.twig', compact('data'));
    }

    /**
     * @Route("/une/show", name="une_show")
     */
    public function show(UneRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $repo->findAll();

        $unes = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('une/show.html.twig', [
            'unes' => $unes,
        ]);
    }

    /**
     * Permet de créer une news
     * 
     * @Route("/une/new", name="une_new")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $une = new Une();

        $une->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(UneType::class, $une);

        $form->handleRequest($request);

        $une->setAuthor($this->getUser());
        
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($une);
            $manager->flush();
            
            return $this->redirectToRoute('une_show', [
                'slug' => $une->getSlug()
                ]);
                
                $this->addflash(
                    'success',
                    "Votre news a bien été ajouté !"
                );
        }

        return $this->render('une/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une news
     * 
     * @Route("/une/{slug}/delete", name="une_delete")
     * @Security("is_granted('ROLE_USER') and user === une.getAuthor()")
     *  
     * @param Une $une
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Une $une, EntityManagerInterface $manager) {
        $manager->remove($une);
        $manager->flush();

        $this->addFlash(
            'success',
            "La news <strong>{$une->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("security_account");
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/une/{slug}/edit", name="une_edit")
     * @Security("is_granted('ROLE_USER') and user === une.getAuthor()")
     * 
     * @return Response
     */
    public function edit(Une $une, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(uneType::class, $une);

        $une->setCreatedAt(new \DateTime('now'));

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($une);
            $manager->flush();

            $this->addFlash(
                'success',
                "La news <strong>{$une->getTitle()}</strong> a bien été enregistrée !"
            );
            
            return $this->redirectToRoute('une_show', [
                'slug' => $une->getSlug()
            ]);

        }

        return $this->render('une/edit.html.twig', [
            'une' => $une,
            'form' => $form->createView()
            ]);
    }

}
