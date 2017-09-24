<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task; 
use AppBundle\Entity\Category; 
use AppBundle\Entity\Comment;

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
                ->findBy(['user' => $this->getUser()]);
        $category = $this->getDoctrine()
                ->getRepository('AppBundle:Category')
                ->findAll();          
        return $this->render('index.html.twig', array('tasks' => $tasks, 'category' => $category,));
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
                ->add('priority')
                ->add('category', null, ['choice_label' => 'name'])
                ->getForm(); 
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $task = $form->getData(); 
            $task->setUser($this->getUser()); 
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
                ->find($id)
                ; 
        
        if (!$task)
        {
            throw $this->createNotFoundException('Task not found');
        }
        if ($task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied');
        }
        
        $form = $this->createFormBuilder($task)
                ->add('name')
                ->add('description')
                ->add('dueDate')
                ->add('checked')
                ->add('priority')
                ->add('category', null, ['choice_label' => 'name'])
                ->getForm(); 
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
         {
             $task = $form->getData();
             $task->setUser($this->getUser());
             $em = $this->getDoctrine()->getManager();
             $em->flush();
             
              return $this->redirectToRoute('app_task_index');
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
                ->find($id)
                ; 
        
        if (!$task)
        {
            throw $this->createNotFoundException('Task not found'); 
        }
        if ($task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied');
        }
        
        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($task);
        $em->flush(); 
        
        return $this->redirectToRoute('app_task_index'); 
    }
}
