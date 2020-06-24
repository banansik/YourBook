<?php

/**
 * Author service.
 */

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AuthorService
 *
 */
class AuthorService
{
    /**
     * Author repository.
     *
     * @var \App\Repository\AuthorRepository
     */
    private $authorRepository;

    /**
     * Paginator interface.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * AuthorService constructor.
     * @param AuthorRepository   $authorRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(AuthorRepository $authorRepository, PaginatorInterface $paginator)
    {
        $this->authorRepository = $authorRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return $this
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->authorRepository->queryAll(),
            $page,
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save author.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Author $author): void
    {
        $this->authorRepository->save($author);
    }

    /**
     * Delete author.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Author $author): void
    {
        $this->authorRepository->delete($author);
    }
}
