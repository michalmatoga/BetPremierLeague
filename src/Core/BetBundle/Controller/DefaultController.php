<?php

namespace Core\BetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {

      return array();
    }

    /**
     * @Route("/demo", name="demo")
     * @Template()
     */
    public function demoAction()
    {

      return array();
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {

      return array();
    }
}
