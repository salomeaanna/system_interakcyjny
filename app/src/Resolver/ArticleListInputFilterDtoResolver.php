<?php
/**
 * TaskListInputFiltersDto resolver.
 */

namespace App\Resolver;

use App\Dto\ArticleListInputFiltersDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * ArticleListInputFilterDtoResolver class.
 */
class ArticleListInputFilterDtoResolver implements ValueResolverInterface
{
    /**
     * Returns the possible value(s).
     *
     * @param Request          $request  Http request
     * @param ArgumentMetadata $argument Argument metadata
     *
     * @return iterable Possible values
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, ArticleListInputFiltersDto::class, true)) {
            return [];
        }

        $categoryId = $request->query->get('categoryId');

        return [new ArticleListInputFiltersDto($categoryId)];
    }
}
