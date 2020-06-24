<?php
/**
 * Reservation Controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use App\Service\BookService;
use App\Service\ReservationService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ReservationController.
 *
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @var ReservationService
     */
    private $reservationService;
    /**
     * @var BookService
     */
    private $bookService;

    /**
     * ReservationController constructor.
     * @param BookService        $bookService
     * @param ReservationService $reservationService
     */
    public function __construct(BookService $bookService, ReservationService $reservationService)
    {
        $this->bookService = $bookService;
        $this->reservationService = $reservationService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="reservation_index",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->reservationService->createPaginatedList($page);

        return $this->render(
            'reservation/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @return Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="reservation_show",
     *     requirements={"id": "[1-9]\d*"},
     *     )
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render(
            'reservation/show.html.twig',
            ['reservation' => $reservation]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @param Book    $book
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/create",
     *     methods={"GET", "POST"},
     *     name="reservation_create",
     *     )
     *
     *
     */
    public function create(Request $request, Book $book): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$book->setAvailability(0);
            $reservation->setBook($book);
            $this->reservationService->save($reservation);

            $this->addFlash('sucess', 'message_created_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'reservation/create.html.twig',
            ['form' => $form->createView(),
            'book' => $book,
            ]
        );
    }

    /**
     * Return action.
     *
     * @param Request $request HTTP request
     * @param Book    $book
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/return",
     *     methods={"GET", "POST"},
     *     name="return",
     *     )
     * @IsGranted("ROLE_ADMIN")
     */
    public function return(Request $request, Book $book): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setAvailability(1);
            $reservation->setBook($book);
            $this->reservationService->save($reservation);
            $this->bookService->save($book);

            $this->addFlash('sucess', 'message_created_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/create.html.twig',
            ['form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request     $request     HTTP request
     * @param Reservation $reservation Reservation entity
     *
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="reservation_edit",
     *     )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->save($reservation);
            //$reservationRepository->save($reservation);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/edit.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request     $request     HTTP request
     * @param Reservation $reservation Reservation entity
     *
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE","POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="reservation_delete",
     *     )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(FormType::class, $reservation, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->delete($reservation);

            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/delete.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }
}
