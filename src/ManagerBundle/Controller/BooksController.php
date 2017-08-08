<?php

namespace ManagerBundle\Controller;

use ManagerBundle\Entity\Book;
use ManagerBundle\Form\BookAddType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/books")
 */
class BooksController extends Controller
{
    /**
     * @Route("/{page}", name="books_list", requirements={"page": "\d+"})
     */
    public function showBooksAction($page = 1)
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();

        if (!$books) {
            return $this->render('@Manager/Default/booksList.html.twig', array(
                'books' => '',
                'statuses' => '',
            ));
        }
        $statuses = ['free', 'reserved', 'taken'];

        return $this->render('@Manager/Default/booksList.html.twig', array(
            'books' => $books,
            'statuses' => $statuses
        ));
    }

    /**
     * @Route("/add", name="books_add")
     */
    public function addBooksAction(Request $request)
    {
        $form = new Book();
        $form = $this->createForm(BookAddType::class, $form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkForm = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($checkForm);
            $em->flush();

            return $this->redirectToRoute('books_list');
        }

        return $this->render('@Manager/Default/bookAdd.html.twig',
            array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/ajax/change_status", name="ajax_change")
     */
    public function ajaxChangeStatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');

        if (isset($id) && isset($status)) {
            $repository = $this->getDoctrine()->getRepository(Book::class);
            $book = $repository->findOneById($id);
            $workflow = $this->container->get('workflow.book_status');

            if ($book && ($workflow->can($book, $status))) {
                $workflow->apply($book, $status);
                $em = $this->getDoctrine()->getManager();
                $em->persist($book);
                $em->flush();
                echo 'success';
            } else {
                echo 'error';
            }
        }
        exit;
    }
}