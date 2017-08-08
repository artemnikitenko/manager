<?php

namespace ManagerBundle\Controller;

use ManagerBundle\Entity\Book;
use ManagerBundle\Form\BookAddType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//require_once __DIR__.'/../../../vendor/autoload.php';

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
            throw $this->createNotFoundException(
                'No product found for id '.$books
            );
        }
        $statuses = array('free', 'reserved', 'taken');
        return $this->render('@Manager/Default/booksList.html.twig', array(
            'books' => $books,
            'statuses' => $statuses
        ));

        $book = new Book();

        $workflow = $this->container->get('workflow.book_status');
//        $workflow->can($book, 'reserved'); // False

        $workflow->can($book, 'free'); // True
        $workflow->apply($book, 'free');
        $workflow->apply($book, 'free');
//        $workflow->apply($book, 'taken');

// Update the currentState on the post
        try {
//            $workflow->apply($book, 'to_review');
        } catch (LogicException $e) {
            // ...
        }

        return $this->render('', array(
            'info' => 'info'
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
     * @Route("/ajax_change_status", name="ajax_change")
     */
    public function ajaxChangeStatus($status)
    {
        echo('<pre>');
        var_dump($status);
        exit;
    }

}