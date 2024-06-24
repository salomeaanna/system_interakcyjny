<?php
/**
 * Article list filters DTO.
 */

namespace App\Dto;

use App\Entity\Category;

/**
 * ArticleListFiltersDto class.
 */
class ArticleListFiltersDto
{
    /**
     * Constructor.
     *
     * @param Category|null $category Category entity
     */
    public function __construct(public readonly ?Category $category)
    {
    }
}
