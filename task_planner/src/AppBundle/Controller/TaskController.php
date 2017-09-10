<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task; 

class TaskController extends Controller
{
    /**
     * 
     * @Route("/")
     */
    public function indexAction()
    {
        $tasks = $this->getDoctrine()
                ->getRepository('AppBundle:Task')
                ->findAll(); 
        return $this->render('index.html.twig', array('tasks' => $tasks));
    }
    /**
     * 
     * @Route("/add")
     */
    public function addTastAction(Request $request) {
        
        $task = new Task(); 
        
        $form = $this->createFormBuilder($task)
                ->add('name')
                ->add('description')
                ->add('dueDate')
                ->getForm(); 
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $task = $form->getData(); 
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return new Response('<body>Task added</body>');
        }
        return $this->render('new_task.html.twig', array(
            'form' => $form->createView(), 
             ));
        
    }
}
