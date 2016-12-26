<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\UserBloger;
use AppBundle\Entity\Coment;
use AppBundle\Form\ComentType;
use AppBundle\Form\PostType;
use AppBundle\Form\UserBlogerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
