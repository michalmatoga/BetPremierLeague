<?php

namespace Core\BetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BetController extends Controller
{
  /**
   * @Route("/bet", name="bet")
   * @Template()
   */
  public function indexAction()
  {
    return array();
  }
}
