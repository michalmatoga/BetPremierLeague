<?php

namespace Core\BetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Core\BetBundle\Entity\Players;

class PlayerController extends Controller
{
  /**
   * @Route("/players/create/{nick}/{password}", name="player_create")
   * @Template()
   */
  public function createAction($nick, $password)
  {
    $player = new Players();
    $player->setNick($nick);
    $player->setPassword(hash('sha256', $nick));
    $em = $this->getDoctrine()->getManager();
    $em->persist($player);
    $em->flush();
    return array('nick' => $nick, 'playerId' => $player->getId());
  }
}
