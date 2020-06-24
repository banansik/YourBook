<?php
/**
 * Author.
 */

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Service\AuthorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController.
 *
 * @Route("/author")
 */
class AuthorController extends AbstractController
{
    /**
     * Author service.
     *
     * @var \App\Service\AuthorService
     */
    private $authorService;

    /**
     * AuthorController constructor.
     * @param AuthorService $authorService
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Show all authors.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="author_index",
     *     methods={"GET"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->authorService->createPaginatedList($page);

        return $this->render(
            'author/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="author_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->save($author);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Author                        $author  Author entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     name="author_edit",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$author->setUpdatedAt(new \DateTime());
            $this->authorService->save($author);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/edit.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Author                        $author  Entity author
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     name="author_delete"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Author $author): Response
    {
        if ($author->getBooks()->count()) {
            $this->addFlash('warning', 'message_author_contains_books');

            return $this->redirectToRoute('author_index');
        }

        $form = $this->createForm(FormType::class, $author, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->delete($author);

            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/delete.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }

    /**
     * Show selected author.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @return \Symfony\Component\HttpFoundation\Response Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="author_show",
     * )
     */
    public function show(Author $author): Response
    {
        return $this->render(
            'author/show.html.twig',
            [
                'author' => $author,
            ]
        );
    }
}
