<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostTag;
use AppBundle\Entity\User;
use AppBundle\Entity\PostCategory;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function updatePostAction(Request $request, Post $post)
    {
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
     * @Method({"POST"})
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function deletePostAction(Request $request, Post $post)    {

        $this->get('app.dbManager')->delete($post);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Getting posts by category.
     *
     * @Route("/post/category/{slug}", name="category")
     * @ParamConverter("category", options={"mapping": {"slug": "description"}})
     */
    public function categoryAction(Request $request, PostCategory $category, $slug)
    {
        $thisPage = $request->query->get('page');
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsByCategory($category, $thisPage);
        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'slug' => $slug, 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]));
    }

    /**
     * Getting posts by author.
     *
     * @Route("/post/author/{slug}", name="author")
     * @ParamConverter("user", options={"mapping": {"slug": "nickName"}})
     */
    public function authorAction(Request $request, User $user, $slug)
    {
        $thisPage = $request->query->get('page');
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsByUser($user, $thisPage);
        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $posts, 'slug' => $slug, 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]));
    }

    /**
     * Getting posts by tag.
     *
     * @Route("/post/tag/{slug}", name="tag")
     * @ParamConverter("postTag", options={"mapping": {"slug": "name"}})
     */
    public function tagAction(Request $request, PostTag $postTag, $slug)
    {
        $thisPage = $request->query->get('page');
        $posts = $postTag->getPosts();
        $pagesParameters = $this->get('app.pgManager')->paginate($thisPage, $posts);
        $criteria = Criteria::create()->orderBy(array('id' => Criteria::DESC))->setFirstResult($pagesParameters[2] * ($thisPage - 1))->setMaxResults($pagesParameters[2]);
        $match_posts = $posts->matching($criteria);

        return $this->render('AppBundle:Pages:index.html.twig', array('blogs' => $match_posts, 'slug' => $slug, 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]));
    }
}
