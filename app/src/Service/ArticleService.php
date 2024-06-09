<?php
/**
 * Article Service.
 */

namespace App\Service;

use App\Dto\ArticleListInputFiltersDto;
use App\Dto\ArticleListFiltersDto;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class Article service.
 */
class ArticleService implements ArticleServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ArticleRepository        $articleRepository Article repository
     * @param PaginatorInterface       $paginator         Paginator interface
     * @param CategoryServiceInterface $categoryService   Category service
     */
    public function __construct(private readonly ArticleRepository $articleRepository, private readonly PaginatorInterface $paginator, private readonly CategoryServiceInterface $categoryService)
    {
    }

    /**
     * Get paginated List.
     *
     * @param int                        $page    Page number
     * @param ArticleListInputFiltersDto $filters Filters
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, ArticleListInputFiltersDto $filters): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->articleRepository->queryAll($filters),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Article $article Article entity
     */
    public function save(Article $article): void
    {
        if (null === $article->getId()) {
            $article->setCreatedAt(new \DateTimeImmutable());
        }
        $article->setUpdatedAt(new \DateTimeImmutable());

        $this->articleRepository->save($article);
    }

    /**
     * Delete action.
     *
     * @param Article $article Article entity
     */
    public function delete(Article $article): void
    {
        $this->articleRepository->delete($article);
    }

    /**
     * Prepare filters for the article list.
     *
     * @param ArticleListInputFiltersDto $filters Raw filters form request
     *
     * @return ArticleListFiltersDto Result filters
     */
    public function prepareFilters(ArticleListInputFiltersDto $filters): ArticleListFiltersDto
    {
        return new ArticleListFiltersDto(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null
        );
    }
}
