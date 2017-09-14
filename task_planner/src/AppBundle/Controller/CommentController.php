<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Task; 

class CommentController extends Controller
{

    /**
     * 
     * @Route("/comment/{id}/add")
     */
    public function addNewCommentAction(Request $request, $id)
    {
        $comment = new Comment(); 
        $task = new Task();
        $task = $this->getDoctrine()
                ->getRepository('AppBundle:Task')
                ->find($id); 
        
        if (!$task) {
            throw $this->createNotFoundException('Author not found');
        }
        
        $form = $this->createFormBuilder($comment)
                ->add('text')
                ->getForm(); 
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData(); 
            $comment->setTask($task);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('app_task_index');
        }
        return $this->render('add_comment.html.twig', array(
            'form' => $form->createView()
             ));
    }
    /**
     * 
     * @Route("/comment/{id}/show")
     */
    public function showCommentsByTaskAction($id)
    {
        $comments = $this->getDoctrine()
                ->getRepository('AppBundle:Comment')
                ->findBy(['task' => $id]);
        var_dump($comments);
        return $this->render('show_comments.html.twig', ['comments' => $comments]); 
    }

}
