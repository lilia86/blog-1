<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Coment;
use AppBundle\Entity\Post;
use AppBundle\Form\ComentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ComentController extends Controller
{
    /**
     * Creating new coment to current post.
     *
     * @Route("/id/{id}", name="id")
     * @Method({"GET", "POST"})
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function idAction(Request $request, Post $post)
    {
        $user = $this->getUser();
        $coment = new Coment();
        $form = $this->createForm(ComentType::class, $coment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->save($coment, $user, $post);
        }

        if ($request->isXmlHttpRequest()) {
            $this->getDoctrine()->getRepository('AppBundle:PostPoint')->saveNotRepeatedPoint($user, $post);
            $points = $post->getPoints();
            $points = count($points);

            return $this->json(array('points' => $points));
        }

        return $this->render('AppBundle:Pages:single.html.twig', array('blog' => $post, 'name' => null, 'form' => $form->createView()));
    }

    /**
     * Update coment.
     *
     * @Route("/coment/update/{id}", name="update_coment")
     * @Method({"GET", "POST"})
     * @ParamConverter("coment", class="AppBundle:Coment")
     */
    public function updateComentAction(Request $request, Coment $coment)
    {
        if (!($this->get('security.authorization_checker')->isGranted('edit', $coment) ||
            $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(ComentType::class, $coment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->update();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete coment.
     *
     * @Route("/coment/delete/{id}", name="delete_coment")
     * @Method({"GET", "POST"})
     * @ParamConverter("coment", class="AppBundle:Coment")
     */
    public function deleteComentAction(Request $request, Coment $coment)
    {
        if (!($this->get('security.authorization_checker')->isGranted('edit', $coment) ||
            $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $this->get('app.dbManager')->delete($coment);

        $form = $this->createForm(ComentType::class, $coment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->update();

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));


    }

    /**
     * Show subcoments.
     *
     * @Route("/sub_coment/{post_id}/{coment_id}", name="sub_coment")
     * @Method({"GET", "POST"})
     * @ParamConverter("post", options={"id" = "post_id"})
     * @ParamConverter("coment", options={"id" = "coment_id"})
     */
    public function subComentAction(Request $request, Post $post, Coment $coment)
    {
        return $this->render('AppBundle:Pages:childcoments.html.twig', array('blog' => $post, 'coment' => $coment));
    }

    /**
     * Creating subcoment.
     *
     * @Route("/sub_coment_form/{post_id}/{coment_id}", name="sub_coment_form")
     * @Method({"GET", "POST"})
     * @ParamConverter("post", options={"id" = "post_id"})
     * @ParamConverter("coment", options={"id" = "coment_id"})
     */
    public function createSubcomentAction(Request $request, Post $post, Coment $coment)
    {
        $user = $this->getUser();
        $subcoment = new Coment();
        $form = $this->createForm(ComentType::class, $subcoment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->save($subcoment, $user, $post, $coment);

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('AppBundle:Pages:childcomentform.html.twig', array('post' => $post, 'coment' => $coment, 'form' => $form->createView()));
    }
}
