<?php
/**
 * Article controller.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Form\Type\ArticleType;
use App\Service\ArticleServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ArticleController.
 */
class ArticleController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param ArticleServiceInterface $articleService Article service
     * @param TranslatorInterface     $translator     Translator
     */
    public function __construct(private readonly ArticleServiceInterface $articleService, private readonly TranslatorInterface $translator)
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

    /**
     * Show action.
     *
     * @param Article $article Article entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'article_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Article $article): Response
    {
        return $this->render('Article/show.html.twig', ['article' => $article]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'article_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(
            ArticleType::class,
            $article,
            ['action' => $this->generateUrl('article_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render('Article/create.html.twig',  ['form' => $form->createView()]);
    }
}
