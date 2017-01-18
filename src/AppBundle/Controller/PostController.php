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
            if (!$this->isGranted('ROLE_USER_BLOGER')) {
                throw $this->createAccessDeniedException();
            }
            $post = new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $this->getUser();
                $this->get('app.dbManager')->save($post, $user);

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
        if (!($this->get('security.authorization_checker')->isGranted('edit', $post) ||
            $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $image = $post->getImage();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($post->getImage() == null){
                $post->setImage($image);

            }
            $this->get('app.dbManager')->update();

            return $this->redirectToRoute('homepage');
        }
        dump($image);
        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * Change post status.
     *
     * @Route("/change_status_post/{id}", name="change_status_post")
     * @Method({"GET", "POST"})
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function updateUserByAdminAction(Request $request, Post $post)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $image = $post->getImage();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($post->getIsPublished()){
                $post->setIsPublished(false);
            }else{
                $post->setIsPublished(true);
            }
            if($post->getImage() == null){
                $post->setImage($image);

            }
            $this->get('app.dbManager')->update();

            return $this->redirectToRoute('admin');
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
     * @ParamConverter("post", class="AppBundle:Post")
     */
    public function deletePostAction(Request $request, Post $post)
    {
        if (!($this->get('security.authorization_checker')->isGranted('edit', $post) ||
            $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.dbManager')->delete($post);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Pages:form.html.twig', array(
            'form' => $form->createView(),
        ));
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
     * @ParamConverter("user", options={"mapping": {"slug": "username"}})
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
