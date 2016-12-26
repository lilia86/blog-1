<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\UserBloger;
use AppBundle\Entity\User;
use AppBundle\Entity\Coment;
use AppBundle\Form\ComentType;
use AppBundle\Form\PostType;
use AppBundle\Form\UserBlogerType;
use AppBundle\Form\UserUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    /**
     * Creates a new user entity.
     *
     * @Route("/new_user", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newUserAction(Request $request)
    {

        $user = new UserBloger();
        $form = $this->createForm(UserBlogerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Update a user entity.
     *
     * @Route("/update_user/{id}", name="user_update")
     * @Method({"GET", "POST"})
     */
    public function updateUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:UserBloger')->find($id);
        $form = $this->createForm(UserBlogerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
