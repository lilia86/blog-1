<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserBloger;
use AppBundle\Form\UserBlogerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
            $this->get('app.dbManager')->save($user);

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
        $user = $this->getDoctrine()->getRepository('AppBundle:UserBloger')->find($id);
        $form = $this->createForm(UserBlogerType::class, $user);
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
     * Delete user.
     *
     * @Route("/delete_user/{id}", name="delete_user")
     * @Method({"GET", "POST"})
     */
    public function deleteUserAction(Request $request, $id)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:UserBloger')
            ->find($id);
        $this->get('app.dbManager')->delete($user);

        return $this->redirect($request->headers->get('referer'));
    }
}
