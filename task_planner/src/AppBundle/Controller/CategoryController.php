<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Category;

class CategoryController extends Controller
{
    /**
     * 
     * @Route("/category/new")
     */
    public function addCategoryAction(Request $request)
    {
        $category = new Category(); 
        
        $form = $this->createFormBuilder()
                ->add('name')
                ->getForm(); 
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData(); 
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_task_index'); 
        }
        return $this->render('add_category.html.twig', array(
            'form' => $form->createView(), 
             ));
    }
}
