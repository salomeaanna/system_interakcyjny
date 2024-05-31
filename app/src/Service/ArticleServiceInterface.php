<?php
/**
 * Article service interface.
 */

namespace App\Service;

use App\Dto\ArticleListFiltersDto;
use App\Dto\ArticleListInputFiltersDto;
use App\Entity\Article;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ArticleServiceInterface.
 */
interface ArticleServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int                        $page    Page number
     * @param ArticleListInputFiltersDto $filters Filters
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, ArticleListInputFiltersDto $filters): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Article $article Article entity
     */
    public function save(Article $article): void;

    /**
     * Delete entity.
     *
     * @param Article $article Article entity
     */
    public function delete(Article $article): void;

    /**
     * Prepare filters for the article list.
     *
     * @param ArticleListInputFiltersDto $filters Raw filters form request
     *
     * @return ArticleListFiltersDto Result filters
     */
    public function prepareFilters(ArticleListInputFiltersDto $filters): ArticleListFiltersDto;
}
