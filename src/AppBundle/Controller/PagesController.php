<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends Controller
{
    /**
     * Getting all existing posts.
     *
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request, $page = 1)
    {
        $thisPage = $request->query->get('page');
        if ($thisPage === null) {
            $thisPage = $page;
        }
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getAllPosts($thisPage);
        $limit = 3;
        $maxPages = ceil($posts->count() / $limit);

        return array('blogs' => $posts, 'slug' => '', 'maxPages' => $maxPages, 'thisPage' => $thisPage);
    }

    /**
     * Getting posts by category.
     *
     * @Route("/category/{slug}", name="category")
     */
    public function categoryAction(Request $request, $slug, $page = 1)
    {
        $thisPage = $request->query->get('page');
        if ($thisPage === null) {
            $thisPage = $page;
        }
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:PostCategory')->findCategoryByName($slug);
        $posts = $em->getRepository('AppBundle:Post')->getPostsByCategory($category[0], $thisPage);
        $limit = 3;
        $maxPages = ceil($posts->count() / $limit);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'slug' => $slug, 'maxPages' => $maxPages, 'thisPage' => $thisPage));
    }

    /**
     * Getting posts by author.
     *
     * @Route("/author/{slug}", name="author")
     */
    public function Action(Request $request, $slug, $page = 1)
    {
        $thisPage = $request->query->get('page');
        if ($thisPage === null) {
            $thisPage = $page;
        }
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository('AppBundle:UserBloger')->findUserByName($slug);
        $posts = $em->getRepository('AppBundle:Post')->getPostsByUser($author[0], $thisPage);
        $limit = 3;
        $maxPages = ceil($posts->count() / $limit);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'slug' => $slug, 'maxPages' => $maxPages, 'thisPage' => $thisPage));
    }

    /**
     * Getting posts by tag.
     *
     * @Route("/tag/{slug}", name="tag")
     */
    public function tagAction(Request $request, $slug, $page = 1)
    {
        $thisPage = $request->query->get('page');
        if ($thisPage === null) {
            $thisPage = $page;
        }
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:PostTag')->findTagByName($slug);
        $posts = $tag[0]->getPosts();
        $limit = 3;
        $maxPages = ceil($posts->count() / $limit);
        $criteria = Criteria::create()->orderBy(array('id' => Criteria::DESC))->setFirstResult($limit * ($thisPage - 1))->setMaxResults($limit);
        $match_posts = $posts->matching($criteria);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $match_posts, 'slug' => $slug, 'maxPages' => $maxPages, 'thisPage' => $thisPage));
    }

    /**
     * Getting post by id.
     *
     * @Route("/{id}", name="id")
     */
    public function idAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        return $this->render('AppBundle:Pages:single.html.twig', array('blog' => $post));
    }

    /**
     * Add 5 last news to sidebar.
     *
     * @return array
     * @Template()
     */
    public function sidebarAction()
    {
        $news = $this->getDoctrine()->getRepository('AppBundle:News')->getFiveLastNews();

        return array('items' => $news);
    }
}
