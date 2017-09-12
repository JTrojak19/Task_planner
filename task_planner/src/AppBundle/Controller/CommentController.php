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
    public function addNewCommentAction(Request $request)
    {
        $comment = new Comment(); 
        $task = new Task(); 
        
        $form = $this->createFormBuilder($comment)
                ->add('text')
                ->add('task', null, ['choice_label' => 'name'])
                ->getForm(); 
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData(); 
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('app_task_index');
        }
        return $this->render('add_comment.html.twig', array(
            'form' => $form->createView()
             ));
    }
}
