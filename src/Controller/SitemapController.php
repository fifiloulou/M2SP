<?php

namespace App\Controller;

use App\Repository\UneRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request, ArticleRepository $articleRepository, UneRepository $uneRepository): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = ['loc' => $this->generateUrl('app')];
        $urls[] = ['loc' => $this->generateUrl('unes_index')];
        $urls[] = ['loc' => $this->generateUrl('articles_index')];
        $urls[] = ['loc' => $this->generateUrl('recettes_index')];

        foreach ($uneRepository->findAll() as $key => $une) {
            $urls[] = [
                'loc' => $this->generateUrl('une_show', ['slug' => $une->getSlug()]),
                'lastmod' => $une->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($articleRepository->findAll() as $key => $article) {
            $urls[] = [
                'loc' => $this->generateUrl('article_show', ['slug' => $article->getSlug()]),
                'lastmod' => $article->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($recetteRepository->findAll() as $key => $recette) {
            $urls[] = [
                'loc' => $this->generateUrl('recette_show', ['slug' => $recette->getSlug()]),
                'lastmod' => $recette->getCreatedAt()->format('Y-m-d')
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname,
            ]),
            200
        );

        $response->headers->set('Content-type', 'text/xml');
         
        return $response;
    }
}
