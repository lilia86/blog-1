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
