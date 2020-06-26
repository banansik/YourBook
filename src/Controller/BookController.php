<?php
/**
 * Book controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Rent;
use App\Entity\User;
use App\Form\BookType;
use App\Form\RentType;
use App\Service\BookService;
use App\Service\RentService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController.
 *
 * @Route("/")
 */
class BookController extends AbstractController
{
    /**
     * @var RentService
     */
    private $rentService;
    /**
     * @var BookService
     */
    private $bookService;

    /**
     * BookController constructor.
     * @param BookService $bookService
     * @param RentService $rentService
     */
    public function __construct(BookService $bookService, RentService $rentService)
    {
        $this->bookService = $bookService;
        $this->rentService = $rentService;
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
     *     name="book_index",
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->bookService->createPaginatedList(
            $request->query->getInt('page', 1),
            $request->query->get('filters', [])
        );

        return $this->render(
            'book/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * IndexAll action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/books",
     *     name="all_book_index",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexAll(Request $request): Response
    {
        $pagination = $this->bookService->createPaginatedListAll(
            $request->query->getInt('page', 1),
            $request->query->get('filters', [])
        );

        return $this->render(
            'book/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Book $book
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="book_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Book $book): Response
    {
        return $this->render(
            'book/show.html.twig',
            ['book' => $book]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/lol",
     *     methods={"GET", "POST"},
     *     name="book_create",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setUpdatedAt(new \DateTime());
            $this->bookService->save($book);
            $this->addFlash('success', 'message_created_seccessfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
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
     *     name="book_edit",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     * )
     */
    public function edit(Request $request, book $book): Response
    {
        $form = $this->createForm(BookType::class, $book, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->save($book);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/edit.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="book_delete",
     * )
     *
     * @IsGranted(
     *     "VIEW",
     *     subject="book",
     * )
     */
    public function delete(Request $request, Book $book): Response
    {
        $form = $this->createForm(FormType::class, $book, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->delete($book);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/delete.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * Add rent.
     *
     * @param Book    $book    Book entity
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/rent",
     *     methods={"GET", "POST"},
     *     name="rent_add"
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function addRent(Book $book, Request $request): Response
    {
        $user = $this->getUser();
        /*
        $rent = $rentRepository->findOneBy([
            'book' => $book,
            'user' => $this->getUser(),
        ]);
        */
        $rent = $this->rentService->alreadyInUsersRents($book, $user);

        if (!$rent) {
            $rent = new Rent();
            $form = $this->createForm(RentType::class, $rent);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $rent->setUser($this->getUser());
                $book->setAvailability(0);
                $this->bookService->save($book);
                $rent->setBook($book);
                //$rentRepository->save($rent);
                $this->rentService->save($rent);

                $this->addFlash('success', 'message_created_successfully');

                return $this->redirectToRoute('book_index');
            }
        } else {
            $this->addFlash('warning', 'message_already_in_rents');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'rent/add.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * User's rents.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/rents",
     *     methods={"GET"},
     *     name="user_rent_index",
     * )
     */
    public function showRents(Request $request)
    {
        $user = $this->getUser();
        $page = $request->query->getInt('page', 1);
        $rent = $this->rentService->createPaginatedList($page, $user);

        return $this->render(
            'user/rent.html.twig',
            [
                'rent' => $rent,
                'user' => $user,
            ]
        );
    }

    /**
     * Delete rent action.
     *
     * @param Request $request HTTP request
     * @param Rent    $rent    Rent entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/rent/{id}/delete",
     *     methods={"GET", "DELETE","POST"},
     *     name="rent_delete"
     * )
     *
     */
    public function deleteRent(Request $request, Rent $rent): Response
    {
        $form = $this->createForm(FormType::class, $rent, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rentService->delete($rent);

            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('user_rent_index');
        }

        return $this->render(
            'user/delete_rent.html.twig',
            [
                'form' => $form->createView(),
                'rent' => $rent,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/editRent",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="book_edit_rent",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="book",
     * )
     */
    public function editRent(Request $request, book $book): Response
    {
        $form = $this->createForm(FormType::class, $book, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setAvailability(1);
            $this->bookService->save($book);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'reservation/return.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     *  AcceptRent action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/acceptRent",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="book_accept_rent",
     * )
     *
     * @IsGranted(
     *     "VIEW",
     *     subject="book",
     * )
     */
    public function acceptRent(Request $request, book $book): Response
    {
        $form = $this->createForm(FormType::class, $book, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setAvailability(0);
            $this->bookService->save($book);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/accept.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }
}
