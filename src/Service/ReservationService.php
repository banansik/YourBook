<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ReservationService.
 */
class ReservationService
{
    /**
     * Reservation Service.
     *
     * @var \App\Service\ReservationService
     */
    private $reservationService;

    /**
     * Book repository.
     *
     * @var \App\Repository\BookRepository
     */
    private $bookRepository;

    /**
     * @var \App\Repository\ReservationRepository
     */
    private $reservationRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * BookService constructor.
     *
     * @param \App\Repository\BookRepository          $bookRepository        Task repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator             Paginator
     * @param ReservationRepository                   $reservationRepository
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator, ReservationRepository $reservationRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->reservationRepository->queryAll(),
            $page,
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save reservation.
     *
     * @param Reservation $reservation
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Reservation $reservation): void
    {
        $this->reservationRepository->save($reservation);
    }

    /**
     * @param Reservation $reservation
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Reservation $reservation): void
    {
        $this->reservationRepository->delete($reservation);
    }
}
