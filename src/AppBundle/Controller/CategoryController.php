<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PostCategory;
use AppBundle\Form\PostCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CategoryController extends Controller
{
    /**
     * Creates a new category entity.
     *
     * @Route("/category/create/", name="category_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newCategoryAction(Request $request)
    {

        $category = new PostCategory();
        $form = $this->createForm(PostCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->save($category);

            return $this->redirectToRoute('admin');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Update a category entity.
     *
     * @Route("/category/update/{id}", name="category_update")
     * @Method({"GET", "POST"})
     * @ParamConverter("category", class="AppBundle:PostCategory")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateUserAction(Request $request, PostCategory $category)
    {
        $form = $this->createForm(PostCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->update();

            return $this->redirectToRoute('admin');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete category.
     *
     * @Route("/category/delete/{id}", name="delete_category")
     * @Method({"GET", "POST"})
     * @ParamConverter("category", class="AppBundle:PostCategory")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteUserAction(Request $request, PostCategory $category)
    {
        $form = $this->createForm(PostCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->delete($category);

            return $this->redirectToRoute('admin');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
