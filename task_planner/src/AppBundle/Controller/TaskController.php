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
    public function addTaskAction(Request $request) {
        
        $task = new Task(); 
        
        $form = $this->createFormBuilder($task)
                ->add('name')
                ->add('description')
                ->add('dueDate')
                ->add('checked')
                ->getForm(); 
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $task = $form->getData(); 
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('app_task_index'); 
        }
        return $this->render('new_task.html.twig', array(
            'form' => $form->createView(), 
             ));
        
    }
    /**
     * 
     * @Route("/{id}/modify")
     */
    public function modifyAction(Request $request, $id)
    {
        $task = new Task(); 
        $task = $this->getDoctrine()
                ->getRepository('AppBundle:Task')
                ->find($id); 
        
        if (!$task)
        {
            throw $this->createNotFoundException('Task not found');
        }
        
        $form = $this->createFormBuilder($task)
                ->add('name')
                ->add('description')
                ->add('dueDate')
                ->add('checked')
                ->getForm(); 
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
         {
             $task = $form->getData();
             $em = $this->getDoctrine()->getManager();
             $em->flush();
             
              return $this->redirectToRoute('app_task_index', array('id' => $task->getId()));
         }
         return $this->render('modify.html.twig', array(
            'form' => $form->createView(), 
             ));
    }
    /**
     * 
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $task = new Task(); 
        $task = $this->getDoctrine()
                ->getRepository('AppBundle:Task')
                ->find($id); 
        
        if (!$task)
        {
            throw $this->createNotFoundException('Task not found'); 
        }
        
        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($task);
        $em->flush(); 
        
        return $this->redirectToRoute('app_task_index'); 
    }
}
