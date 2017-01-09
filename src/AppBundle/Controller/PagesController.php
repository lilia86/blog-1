<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        return array('blogs' => $posts, 'slug' => '', 'maxPages' => $pagesParameters[0], 'thisPage' => $pagesParameters[1]);
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
}
