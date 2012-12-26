<?php

namespace Core\BetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Core\BetBundle\Entity\Players;

class PlayerController extends Controller
{
  /**
   * @Route("/players/register", name="player_register")
   * @Template()
   */
  public function registerAction(Request $request)
  {
    $form = $this->createFormBuilder(new Players())
      ->add('nick', 'text')->add('password', 'password')->getForm();
    if ($request->isMethod('POST')) {
      $form->bind($request);
      if ($form->isValid()) {
        $player = $form->getData();

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($player);
        $password = $encoder->encodePassword($player->getPassword(), $player->getSalt());
        $player->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();
        $this->get('session')->setFlash('success', 'That\'s it! Registration complete!');
        return $this->redirect($this->generateUrl('player_register'));
      }
    }
    return array('form' => $form->createView());
  }

  /**
   * @Route("/players/logout", name="logout")
   * @Template()
   */
  public function logoutAction(){
    return array(); 
  }
}
