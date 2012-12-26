<?php

namespace Core\BetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bets
 *
 * @ORM\Table(name="bets")
 * @ORM\Entity
 */
class Bets
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="bet", type="string", length=1, nullable=false)
     */
    private $bet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var \Matches
     *
     * @ORM\ManyToOne(targetEntity="Matches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */
    private $match;

    /**
     * @var \Players
     *
     * @ORM\ManyToOne(targetEntity="Players")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     * })
     */
    private $player;



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
     * Set bet
     *
     * @param string $bet
     * @return Bets
     */
    public function setBet($bet)
    {
        $this->bet = $bet;
    
        return $this;
    }

    /**
     * Get bet
     *
     * @return string 
     */
    public function getBet()
    {
        return $this->bet;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Bets
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

    /**
     * Set match
     *
     * @param \Core\BetBundle\Entity\Matches $match
     * @return Bets
     */
    public function setMatch(\Core\BetBundle\Entity\Matches $match = null)
    {
        $this->match = $match;
    
        return $this;
    }

    /**
     * Get match
     *
     * @return \Core\BetBundle\Entity\Matches 
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * Set player
     *
     * @param \Core\BetBundle\Entity\Players $player
     * @return Bets
     */
    public function setPlayer(\Core\BetBundle\Entity\Players $player = null)
    {
        $this->player = $player;
    
        return $this;
    }

    /**
     * Get player
     *
     * @return \Core\BetBundle\Entity\Players 
     */
    public function getPlayer()
    {
        return $this->player;
    }
}