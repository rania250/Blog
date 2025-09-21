<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Services\CategoriesServices;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApiController extends AbstractController
{
    public function __construct(CategoriesServices $categoriesServices){
        $categoriesServices->updateSession();
    }

    /**
     * API pour récupérer les slides du slider
     */
    #[Route('/slides', name: 'api_slides', methods: ['GET'])]
    public function getSlides(ArticleRepository $articleRepository, Request $request): JsonResponse
    {
        // les 5 derniers articles pour le slider
        $articles = $articleRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );

        $slides = [];
        foreach ($articles as $article) {
            $slides[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'description' => substr(strip_tags($article->getContent()), 0, 150) . '...',
                'image' => $article->getImageUrl() ?
                    $request->getSchemeAndHttpHost() . $article->getImageUrl() :
                    null,
                'slug' => $article->getSlug(),
                'url' => $this->generateUrl('app_single_article', ['slug' => $article->getSlug()]),
                'createdAt' => $article->getCreatedAt()->format('Y-m-d H:i:s'),

                'author' => [
                    'id' => $article->getAuthor()->getId(),
                    'fullname' => $article->getAuthor()->getFullName(),
                    'email' => $article->getAuthor()->getEmail()
                ]
            ];
        }

        return new JsonResponse($slides);
    }

    /**
     * API pour récupérer tous les articles
     */
    #[Route('/articles', name: 'api_articles', methods: ['GET'])]
    public function getArticles(ArticleRepository $articleRepository, Request $request): JsonResponse
    {
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        $articlesData = [];
        foreach ($articles as $article) {
            $articlesData[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'description' => substr(strip_tags($article->getContent()), 0, 150) . '...',
                'image' => $article->getImageUrl() ?
                    $request->getSchemeAndHttpHost() . $article->getImageUrl() :
                    null,
                'slug' => $article->getSlug(),
                'createdAt' => $article->getCreatedAt()->format('Y-m-d H:i:s'),

                'author' => [
                    'id' => $article->getAuthor()->getId(),
                    'fullname' => $article->getAuthor()->getFullName(),
                    'email' => $article->getAuthor()->getEmail()
                ]
            ];
        }

        return new JsonResponse($articlesData);
    }

    /**
     * API pour récupérer un article par slug
     */
    #[Route('/articles/{slug}', name: 'api_article_single', methods: ['GET'])]
    public function getSingleArticle(ArticleRepository $articleRepository, Request $request, string $slug): JsonResponse
    {
        $article = $articleRepository->findOneBySlug($slug);

        if (!$article) {
            return new JsonResponse([
                'error' => 'Article not found',
                'message' => "L'article avec le slug '{$slug}' n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }

        $articleData = [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'description' => substr(strip_tags($article->getContent()), 0, 150) . '...',
            'image' => $article->getImageUrl() ?
                $request->getSchemeAndHttpHost() . $article->getImageUrl() :
                null,
            'slug' => $article->getSlug(),
            'createdAt' => $article->getCreatedAtFrench(),
            'updatedAt' => $article->getUpdatedAt() ? $article->getUpdatedAtFrench() : null,
            // ✅ CORRIGÉ : "fullname" au lieu de "name"
            'author' => [
                'id' => $article->getAuthor()->getId(),
                'fullname' => $article->getAuthor()->getFullName(),
                'email' => $article->getAuthor()->getEmail()
            ]
        ];

        return new JsonResponse($articleData);
    }

}