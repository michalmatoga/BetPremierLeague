<?php

namespace Core\BetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Core\BetBundle\Entity\Matches;
use Core\BetBundle\Entity\Bets;

class BetController extends Controller
{
  /**
   * @Route("/bet", name="bet")
   * @Template()
   * @todo put business logic in more suitable place
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $query = $em->createQuery(
      'SELECT m FROM CoreBetBundle:Matches m WHERE m.date > CURRENT_TIMESTAMP() ORDER BY m.date ASC'
    );
    $matches = $query->getResult();
    $fixtures = array();
    $matchIds = array();
    foreach($matches as $match){
      $matchIds[] = $match->getId();
      $currentDate = $match->getDate()->format('Y-m-d');
      if (!array_key_exists($currentDate, $fixtures)){
        $fixtures[$currentDate] = array();
      }
      $fixtures[$currentDate][] = $match;
    }
    $cache = $this->get('cache');
    $cache->setNamespace('core.cache'); 
    if ($odds = $cache->fetch('currentodds')) {
      $odds = unserialize($odds);
    } else {
      $scrapper = new \Core\BetBundle\WebScrapper();
      $odds = $scrapper->getPremierLeagueOdds();
      $cache->save('currentodds', serialize($odds), 86400);//TTL 24H
    }
    $player = $this->get('security.context')->getToken()->getUser();
    /*
     * Get current player bets
     */
    $query = $em->createQuery(
      'SELECT b FROM CoreBetBundle:Bets b WHERE b.match IN ('.implode(',', $matchIds).') AND b.player = '.$player->getId()
    );
    $bets = $query->getResult();
    $playerBets = array();
    foreach($bets as $bet){
        $playerBets[$bet->getMatch()->getId()] = $bet->getBet(); 
    }

    /*
     * Handle bets
     */
    $errors = array();
    if ($request->isMethod('POST')) {
      $bets = $request->request->all();
      /*
       * Lazy validation
       */
      foreach($bets as $bet){
        if (!in_array(strtolower($bet), array('1','2', 'x'))){
          $errors = array('Some of the bets you submitted were invalid'); 
          break;
        }
      }
      if (empty($errors)){
        $em = $this->getDoctrine()->getEntityManager();
        foreach($bets as $matchId => $bet){
          $newBet = new Bets(); 
          $newBet->setPlayer($player);
          $newBet->setBet(strtolower($bet));
          $id = explode('_', $matchId);
          $match = $this->getDoctrine()->getRepository('CoreBetBundle:Matches')->find($id[1]);
          $newBet->setMatch($match);
          $newBet->setDate(new \DateTime());
          $em->persist($newBet);

          /* remove previous bet if any */
          $query = $em->createQuery(
            'SELECT b FROM CoreBetBundle:Bets b WHERE b.match = '.$id[1].' AND b.player = '.$player->getId()
          );
          $oldBet = $query->getResult();
          if (!empty($oldBet)){
            $em->remove($oldBet[0]);
          }
        }
        $em->flush();
        $this->get('session')->setFlash('success', 'Your bets have been submitted');
        return $this->redirect($this->generateUrl('bet'));
      }
    }
    return array('fixtures' => $fixtures, 'odds' => $odds, 'bets' => $playerBets, 'errors' => $errors);
  }

  /**
   * @Route("/bet/history", name="bet_history")
   * @Template()
   */
  public function historyAction()
  {
    $this->_updateScores();
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

  private function _updateScores(){
    $em = $this->getDoctrine()->getEntityManager();
    $query = $em->createQuery(
      'SELECT m FROM CoreBetBundle:Matches m WHERE m.score1 IS NULL AND m.date <= CURRENT_TIMESTAMP()'
    );
    $scrapper = new \Core\BetBundle\WebScrapper();
    $emptyResults = $query->getResult();
    $filledResults = $scrapper->getPremierLeagueResults($emptyResults);
    if (!empty($filledResults)){
      foreach($filledResults as $result){
        $em->persist($result);
      }
      $em->flush();
    }
  }
}
