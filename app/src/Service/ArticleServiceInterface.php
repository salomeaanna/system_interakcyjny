<?php
/**
 * Article service interface.
 */

namespace App\Service;

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
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    public function save(Article $article): void;
}
