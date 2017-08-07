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
        $book = new Book();

        $workflow = $this->container->get('workflow.book_status');
//        $workflow->can($book, 'reserved'); // False

        $workflow->can($book, 'free'); // True
        $workflow->apply($book, 'free');
        $workflow->apply($book, 'free');
//        $workflow->apply($book, 'taken');
        echo '<pre>';
var_dump( $book); exit;
// Update the currentState on the post
        try {
//            $workflow->apply($book, 'to_review');
        } catch (LogicException $e) {
            // ...
        }

        return $this->render('SkillBundle:Default:test.html.twig', array(
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

//        if ($form->isSubmitted() && $form->isValid()) {
//            $checkForm = $form->getData();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($checkForm);
//            $em->flush();
//
//            return $this->render('', array(
//                'success' => 'Success'
//            ));
//        }

        return $this->render('@Manager/Default/bookAdd.html.twig',
            array(
                'form' => $form->createView()
            ));
    }

}