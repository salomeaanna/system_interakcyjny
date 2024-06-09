<?php
/**
 * Article controller.
 */

namespace App\Controller;

use App\Dto\ArticleListInputFiltersDto;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\Type\ArticleType;
use App\Form\Type\CommentType;
use App\Resolver\ArticleListInputFilterDtoResolver;
use App\Service\ArticleServiceInterface;
use App\Service\CommentServiceInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
     * @param ArticleListInputFiltersDto $filters Filters
     * @param int                        $page    HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/',
        name: 'article_index',
        methods: 'GET'
    )]
    public function index(#[MapQueryString(resolver: ArticleListInputFilterDtoResolver::class)] ArticleListInputFiltersDto $filters, #[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->articleService->getPaginatedList(
            $page,
            $filters
        );

        return $this->render('article/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Article                 $article        Article entity
     * @param Request                 $request        Http request
     * @param CommentServiceInterface $commentService Comment service interface
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'article_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function show(Article $article, Request $request, CommentServiceInterface $commentService): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment, ['method' => 'POST']);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setArticle($article);
            $commentService->save($comment);

            $this->addFlash('success', 'message.comment_added');

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $commentService->getForArticle($article),
            'form' => $commentForm->createView(),
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'article_create', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
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

        return $this->render('article/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * @param Request $request Http request
     * @param Article $article Article entity
     *
     * @return Response Http response
     */
    #[Route(
        '/{id}/edit',
        name: 'article_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('EDIT', subject: 'article')]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(
            ArticleType::class,
            $article,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('article_edit', ['id' => $article->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'article/edit.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request Http request
     * @param Article $article Article entity
     *
     * @return Response Http response
     */
    #[Route(
        '/{id}/delete',
        name: 'article_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    #[IsGranted('DELETE', subject: 'article')]
    public function delete(Request $request, Article $article): Response
    {
        $form = $this->createForm(
            FormType::class,
            $article,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('article_delete', ['id' => $article->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->delete($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'article/delete.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }
}
