<?php
/**
 * Article list input filters DTO.
 */

namespace App\Dto;

/**
 * Class ArticleListInputFiltersDto.
 */
class ArticleListInputFiltersDto
{
    /**
     * Constructor.
     *
     * @param int|null $categoryId Category identifier
     */
    public function __construct(public readonly ?int $categoryId = null)
    {
    }
}
