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

class PostController extends Controller
{
       /**
         * Creates a new post entity.
         *
         * @Route("/new_post", name="post_new")
         * @Method({"GET", "POST"})
         */
        public function newPostAction(Request $request)
    {

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Update post entity.
     *
     * @Route("/update_post/{id}", name="update_post")
     * @Method({"GET", "POST"})
     */
    public function updatePostAction(Request $request, $id)
    {

        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
        ));
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
    public function authorAction(Request $request, $slug, $page = 1)
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

}
