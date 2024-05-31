<?php
/**
 * Article repository.
 */

namespace App\Repository;

use App\Entity\Article;
use App\Dto\ArticleListFiltersDto;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ArticleRepository.
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Article>
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Query all records.
     *
     * @param ArticleListFiltersDto $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(ArticleListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select('category', 'article')
            ->join('article.category', 'category')
            ->orderBy('article.updatedAt', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Save entity.
     *
     * @param Article $article Article entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Article $article): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($article);
        $this->_em->flush();
    }

    /**
     * Delete action.
     *
     * @param Article $article article entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Article $article): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($article);
        $this->_em->flush();
    }

    /**
     * Count tasks by category.
     *
     * @param Category $category Category
     *
     * @return int Number of tasks in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('article.id'))
            ->where('article.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('article');
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param ArticleListFiltersDto $filters      Filters
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, ArticleListFiltersDto $filters): QueryBuilder
    {
        if ($filters->category instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters->category);
        }

        return $queryBuilder;
    }
}
