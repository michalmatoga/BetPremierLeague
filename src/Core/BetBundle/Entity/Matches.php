<?php

namespace Core\BetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Core\BetBundle\Entity\Matches
 *
 * @ORM\Table(name="matches")
 * @ORM\Entity
 */
class Matches
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $team1
     *
     * @ORM\Column(name="team_1", type="string", length=255, nullable=false)
     */
    private $team1;

    /**
     * @var boolean $score1
     *
     * @ORM\Column(name="score_1", type="boolean", nullable=false)
     */
    private $score1;

    /**
     * @var boolean $score2
     *
     * @ORM\Column(name="score_2", type="boolean", nullable=false)
     */
    private $score2;

    /**
     * @var string $team2
     *
     * @ORM\Column(name="team_2", type="string", length=255, nullable=false)
     */
    private $team2;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    public function getUpcomingMatches(){
    $em = $this->getDoctrine()->getEntityManager();
$query = $em->createQuery(
    'SELECT p FROM AcmeStoreBundle:Product p WHERE p.price > :price ORDER BY p.price ASC'
)->setParameter('price', '19.99');

$products = $query->getResult();
    }

    public function getPastMatches(){
    
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set team1
     *
     * @param string $team1
     * @return Matches
     */
    public function setTeam1($team1)
    {
        $this->team1 = $team1;
    
        return $this;
    }

    /**
     * Get team1
     *
     * @return string 
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * Set score1
     *
     * @param boolean $score1
     * @return Matches
     */
    public function setScore1($score1)
    {
        $this->score1 = $score1;
    
        return $this;
    }

    /**
     * Get score1
     *
     * @return boolean 
     */
    public function getScore1()
    {
        return $this->score1;
    }

    /**
     * Set score2
     *
     * @param boolean $score2
     * @return Matches
     */
    public function setScore2($score2)
    {
        $this->score2 = $score2;
    
        return $this;
    }

    /**
     * Get score2
     *
     * @return boolean 
     */
    public function getScore2()
    {
        return $this->score2;
    }

    /**
     * Set team2
     *
     * @param string $team2
     * @return Matches
     */
    public function setTeam2($team2)
    {
        $this->team2 = $team2;
    
        return $this;
    }

    /**
     * Get team2
     *
     * @return string 
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Matches
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
