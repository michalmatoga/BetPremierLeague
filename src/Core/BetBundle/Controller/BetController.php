<?php

namespace Core\BetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Core\BetBundle\Entity\Matches;

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

  /**
   * Lazy method for persisting scrapped fixtures into database
   * @todo monitoring fixtures for changes and auto-update
   */
  private function fillFixtures(){
    $scrapper = new \Core\BetBundle\WebScrapper();
    $fixtures = $scrapper->getPremierLeagueFixtures();
    $em = $this->getDoctrine()->getManager();
    foreach($fixtures as $fix){
      $match = new Matches();
      $match->setTeam1($fix['team_1']);
      $match->setTeam2($fix['team_2']);
      $match->setDate(new \DateTime($fix['date']));
      $em->persist($match);
    }
    $em->flush();
    exit;
  }
}
