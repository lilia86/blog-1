<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
         * Creates a new post entity.
         *
         * @Route("/post/create", name="post_new")
         * @Method({"GET", "POST"})
         */
        public function newPostAction(Request $request)
        {
            $post = new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->get('app.dbManager')->save($post);

                return $this->redirectToRoute('homepage');
            }

            return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
        }

    /**
     * Update post entity.
     *
     * @Route("/post/update/{id}", name="update_post")
     * @Method({"GET", "POST"})
     */
    public function updatePostAction(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        $form = $this->createForm(PostType::class, $post);
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
     * Delete post.
     *
     * @Route("/post/delete/{id}", name="delete_post")
     * @Method({"GET", "POST"})
     */
    public function deletePostAction(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        $this->get('app.dbManager')->delete($post);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Getting posts by category.
     *
     * @Route("/post/category/{slug}", name="category")
     */
    public function categoryAction(Request $request, $slug)
    {
        $thisPage = $request->query->get('page');
        $category = $this->getDoctrine()->getRepository('AppBundle:PostCategory')->findCategoryByName($slug);
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsByCategory($category[0], $thisPage);
        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'slug' => $slug, 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]));
    }

    /**
     * Getting posts by author.
     *
     * @Route("/post/author/{slug}", name="author")
     */
    public function authorAction(Request $request, $slug)
    {
        $thisPage = $request->query->get('page');
        $author = $this->getDoctrine()->getRepository('AppBundle:UserBloger')->findUserByName($slug);
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsByUser($author[0], $thisPage);
        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'slug' => $slug, 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]));
    }

    /**
     * Getting posts by tag.
     *
     * @Route("/post/tag/{slug}", name="tag")
     */
    public function tagAction(Request $request, $slug)
    {
        $thisPage = $request->query->get('page');
        $tag = $this->getDoctrine()->getRepository('AppBundle:PostTag')->findTagByName($slug);
        $posts = $tag[0]->getPosts();
        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);
        $criteria = Criteria::create()->orderBy(array('id' => Criteria::DESC))->setFirstResult($pagesParameters[2] * ($thisPage - 1))->setMaxResults($pagesParameters[2]);
        $match_posts = $posts->matching($criteria);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $match_posts, 'slug' => $slug, 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]));
    }
}
