<?php
/**
 * Author repository.
 */

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AuthorRepository.
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 6;

    /**
     * AuthorRepository constructor.
     *
     * @param \Doctrine\Persistence\ManagerRegistry $registry manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Author $author): void
    {
        $this->_em->persist($author);
        $this->_em->flush($author);
    }

    /**
     * @param \App\Entity\Author $author Author entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Author $author): void
    {
        $this->_em->remove($author);
        $this->_em->flush($author);
    }



    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builer
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('author')
            ->orderBy('author.title', 'DESC');
    }

    /**
     * Get or create new query.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder // tworzy lub pobiera query buildera
    {
        // jeśli nie ma query buildera to utworz nowy
        return $queryBuilder ?? $this->createQueryBuilder('author'); // task to alias
    }
}
