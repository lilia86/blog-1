<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Coment;
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
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);
        $user = $this->getUser();
        $coment = new Coment();
        $form = $this->createForm(ComentType::class, $coment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coment->setUser($user);
            $coment->setPost($post);
            $em = $this->getDoctrine()->getManager();
            $em->persist($coment);
            $em->flush();
        }

        return $this->render('AppBundle:Pages:single.html.twig', array('blog' => $post, 'form' => $form->createView()));
    }

    /**
     * Update coment.
     *
     * @Route("/update_coment/{id}", name="update_coment")
     * @Method({"GET", "POST"})
     */
    public function updateComentAction(Request $request, $id)
    {
        $coment = $this->getDoctrine()
            ->getRepository('AppBundle:Coment')
            ->find($id);
        $form = $this->createForm(ComentType::class, $coment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($coment);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
