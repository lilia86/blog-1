<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PostTag;
use AppBundle\Form\PostTagType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    /**
     * Creates a new tag entity.
     *
     * @Route("/tag/create/", name="tag_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newTagAction(Request $request)
    {
        $tag = new PostTag();
        $form = $this->createForm(PostTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->save($tag);

            return $this->redirectToRoute('admin');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Update a tag entity.
     *
     * @Route("/tag/update/{id}", name="tag_update")
     * @Method({"GET", "POST"})
     * @ParamConverter("tag", class="AppBundle:PostTag")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateUserAction(Request $request, PostTag $tag)
    {
        $form = $this->createForm(PostTagType::class, $tag);
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
     * Delete tag.
     *
     * @Route("/tag/delete/{id}", name="delete_tag")
     * @Method({"GET", "POST"})
     * @ParamConverter("tag", class="AppBundle:PostTag")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteUserAction(Request $request, PostTag $tag)
    {
        $form = $this->createForm(PostTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->delete($tag);

            return $this->redirectToRoute('admin');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
