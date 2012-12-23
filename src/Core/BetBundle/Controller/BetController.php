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
   * @todo put business logic in more suitable place
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $query = $em->createQuery(
      'SELECT m FROM CoreBetBundle:Matches m WHERE m.date > CURRENT_TIMESTAMP() ORDER BY m.date ASC'
    );
    $matches = $query->getResult();
    $fixtures = array();
    foreach($matches as $match){
    $currentDate = $match->getDate()->format('Y-m-d');
      if (!array_key_exists($currentDate, $fixtures)){
        $fixtures[$currentDate] = array();
      }
      $fixtures[$currentDate][] = $match;
    }
    return array('fixtures' => $fixtures);
  }

  /**
   * @Route("/bet/history", name="bet_history")
   * @Template()
   */
  public function historyAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $query = $em->createQuery(
      'SELECT m FROM CoreBetBundle:Matches m WHERE m.date <= CURRENT_TIMESTAMP() ORDER BY m.date DESC'
    );
    $matches = $query->getResult();
    $fixtures = array();
    foreach($matches as $match){
    $currentDate = $match->getDate()->format('Y-m-d');
      if (!array_key_exists($currentDate, $fixtures)){
        $fixtures[$currentDate] = array();
      }
      $fixtures[$currentDate][] = $match;
    }
    return array('fixtures' => $fixtures);
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
