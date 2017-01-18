<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends Controller
{
    /**
     * Getting all existing posts.
     *
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $thisPage = $request->query->get('page');
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getAllPosts($thisPage);
        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'slug' => '', 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]));
    }

    /**
     * Add 5 last news to sidebar.
     *
     * @return array
     * @Template()
     */
    public function sidebarAction()
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getFiveMostPopular();

        return array('items' => $posts);
    }

    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:Pages:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * Registry.
     *
     * @Route("/user/registry", name="registry")
     * @Method({"GET"})
     */
    public function registryAction()
    {
        return $this->render('AppBundle:Pages:registry.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        return array('null' => null);
    }
    /**
     * @Route("/admin", name="admin")
     * @Template()
     */
    public function adminAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $entity = $request->request->get('entity');
            $id = $request->request->get('id');
            $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
            $status = $post->getIsPublished();
            if($status){
                $post->setIsPublished(false);
            }else{
                $post->setIsPublished(true);
            }

            return $this->json(array('status' => $status));
        }
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        $category = $this->getDoctrine()->getRepository('AppBundle:PostCategory')->findAll();
        $tag = $this->getDoctrine()->getRepository('AppBundle:PostTag')->findAll();
        return array('user' => $user, 'post' => $post, 'tag' => $tag, 'category' => $category);
    }
}
