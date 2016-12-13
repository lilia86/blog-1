<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction($page = 1)
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->getAllPosts();

        $limit = 3;
        $maxPages = ceil($posts->count() / $limit);
        $thisPage = $page;

        return array('blogs' => $posts, 'maxPages' => $maxPages, 'thisPage' => $thisPage);
    }

    /**
     * @Route("/category/{slug}", name="category")
     * @Template()
     */
    public function categoryAction($slug, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:PostCategory')->findCategoryByName($slug);
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->getPostsByCategory($category[0]);
        /* $posts = $category[0]->getPosts();*/
        $limit = 3;
        $maxPages = ceil($posts->count() / $limit);
        $thisPage = $page;

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'maxPages' => $maxPages, 'thisPage' => $thisPage));
    }

    /**
     * @Route("/{id}", name="id")
     * @Template()
     */
    public function idAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        return $this->render('AppBundle:Pages:single.html.twig', array('blog' => $post));
    }

    /**
     * @Route("/tag/{slug}", name="tag")
     * @Template()
     */
    public function tagAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:PostTag')->findTagByName($slug);
        $posts = $tag[0]->getPosts();

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts));
    }

    /**
     * @return array
     * @Template()
     */
    public function sidebarAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findAll();

        return array('items' => $posts);
    }
}
