<?php
/**
 * Category repository.
 */

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CategoryRepository.
 *
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()->orderBy('category.updatedAt', 'DESC');
    }

    /**
     * Save entity.
     *
     * @param Category $category Article entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Category $category): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($category);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('category');
    }
}