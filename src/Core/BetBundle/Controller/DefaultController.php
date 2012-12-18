<?php

namespace Core\BetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;


class DefaultController extends Controller
{
  /**
   * @Route("/", name="home")
   * @Template()
   */
  public function indexAction()
  {
    $request = $this->getRequest();
    $session = $request->getSession();

    if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
      $error = $request->attributes->get(
        SecurityContext::AUTHENTICATION_ERROR
      );
    } else {
      $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
      $session->remove(SecurityContext::AUTHENTICATION_ERROR);
    }


    $cache = $this->get('cache');
    $cache->setNamespace('core.cache'); 
    if ($standings = $cache->fetch('plstandings')) {
        $standings = unserialize($standings);
    } else {
        $scrapper = new \Core\BetBundle\WebScrapper();
        $standings = $scrapper->getPremierLeagueStandings();
        $cache->save('plstandings', serialize($standings), 3600);//TTL 1H
    }
    return array(
      'last_username' => $session->get(SecurityContext::LAST_USERNAME),
      'standings' => $standings,
      'error'         => $error
    );
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
