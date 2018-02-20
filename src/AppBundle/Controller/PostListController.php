<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostListController extends Controller
{
    /**
     * @Route("/post-list", name="post_list")
     */
    public function indexAction()
    {

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            return $this->redirectToRoute('login');

        $myPosts = $this->getDoctrine()->getRepository('AppBundle:Post')->findMyAllPost($this->getUser()->getUsername());

        return $this->render('AppBundle:PostList:index.html.twig', array(
            'post_list' => $myPosts
        ));
    }

}
