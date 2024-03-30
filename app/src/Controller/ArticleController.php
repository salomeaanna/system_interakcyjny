<?php
/**
 * Article controller.
 */

namespace App\Controller;

use App\Service\ArticleServiceInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ArticleController.
 */
class ArticleController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param ArticleServiceInterface $articleService Article service
     */
    public function __construct(private readonly ArticleServiceInterface $articleService)
    {
    }

    /**
     * Index action.
     *
     * @param int $page HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/',
        name: 'article_index',
        methods: 'GET'
    )]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->articleService->getPaginatedList($page);

        return $this->render('Article/index.html.twig', ['pagination' => $pagination]);
    }
}
