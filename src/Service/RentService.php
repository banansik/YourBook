<?php
/**
 * Favourite service.
 */

namespace App\Service;

use App\Entity\Rent;
use App\Repository\RentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BookService.
 */
class RentService
{
    /**
     * Rent repository.
     *
     * @var \App\Repository\RentRepository
     */
    private $rentRepository;

    /**
     * Paginator interface.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * RentService constructor.
     *
     * @param \App\Repository\RentRepository $rentRepository Rent repository
     * @param PaginatorInterface             $paginator
     */
    public function __construct(RentRepository $rentRepository, PaginatorInterface $paginator)
    {
        $this->rentRepository = $rentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     * @param $user
     *
     * @return $this
     */
    public function createPaginatedList(int $page, $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->rentRepository->queryByUser($user),
            $page,
            rentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Already in user's rents.
     *
     * @param $book
     * @param $user
     *
     * @return \App\Entity\rent|null
     */
    public function alreadyInUsersRents($book, $user)
    {
        return $this->rentRepository->findOneBy([
            'book' => $book,
            'user' => $user,
        ]);
    }

    /**
     * Save rent.
     *
     * @param \App\Entity\Rent $rent Rent entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Rent $rent): void
    {
        $this->rentRepository->save($rent);
    }

    /**
     * Delete rent.
     *
     * @param \App\Entity\Rent $rent Rent entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Rent $rent): void
    {
        $this->rentRepository->delete($rent);
    }
}
