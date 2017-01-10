<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Coment;
use AppBundle\Entity\PostPoint;
use AppBundle\Form\ComentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ComentController extends Controller
{
    /**
     * Creating new coment to current post.
     *
     * @Route("/id/{id}", name="id")
     * @Method({"GET", "POST"})
     */
    public function idAction(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
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
     * Creating subcoment.
     *
     * @Route("/subcoment/{post_id}/{coment_id}", name="subcoment")
     * @Method({"GET", "POST"})
     */
    public function createSubcomentAction(Request $request, $post_id, $coment_id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($post_id);
        $coment = $this->getDoctrine()->getRepository('AppBundle:Coment')->find($coment_id);
        $user = $this->getUser();
        $subcoment = new Coment();
        $form = $this->createForm(ComentType::class, $coment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->save($subcoment, $user, $post, $coment);
        }

        return $this->render('AppBundle:Pages:single.html.twig', array('blog' => $post, 'name' => null, 'form' => $form->createView()));
    }

    /**
     * Update coment.
     *
     * @Route("/update_coment/{id}", name="update_coment")
     * @Method({"GET", "POST"})
     */
    public function updateComentAction(Request $request, $id)
    {
        $coment = $this->getDoctrine()->getRepository('AppBundle:Coment')->find($id);
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
     * @Route("/delete_coment/{id}", name="delete_coment")
     * @Method({"GET", "POST"})
     */
    public function deleteComentAction(Request $request, $id)
    {
        $coment = $this->getDoctrine()->getRepository('AppBundle:Coment')->find($id);
        $user = $this->getUser();
        $comenter = $coment->getUser();

        if ($user === $comenter) {
            $this->get('app.dbManager')->delete($coment);
        } else {
            $this->addFlash(
                'notice',
                "Sorry You can't delete this coment!"
            );
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Sub coment.
     *
     * @Route("/sub_coment/{id}", name="sub_coment")
     * @Method({"GET", "POST"})
     */
    public function subComentAction(Request $request, $id)
    {
        $coment = $this->getDoctrine()->getRepository('AppBundle:Coment')->find($id);

        return $this->render('AppBundle:Pages:childcoments.html.twig', array('coment' => $coment));
    }
}
