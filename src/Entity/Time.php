<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 */
class Time
{
    /**
     * @ORM\Id;
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $userid;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $status;

    /**
     * @ORM\Column(type="integer")
     */
    protected $start_work;
    /**
     * @ORM\Column(type="integer")
     */
    protected $stop_work;

    /**
     * @ORM\Column(type="integer")
     */
    protected $start_away;
    /**
     * @ORM\Column(type="integer")
     */
    protected $stop_away;
	/**
     * @ORM\Column(type="integer")
     */
    protected $workhours;
    /**
     * @ORM\Column(type="integer")
     */
    protected $away;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $date;


    public function getId()
    {
        return $this->id;
    }
    public function setStartWork($start_work)
    {
		$this->start_work = $start_work;
    }

    public function getStartWork()
    {
        return $this->start_work;
    }
	
    public function setStopWork($stop_work)
    {
		$this->stop_work = $stop_work;
    }

    public function getStopWork()
    {
        return $this->stop_work;
    }

    public function setStartAway($start_away)
    {
		$this->start_away = $start_away;
    }

    public function getStartAway()
    {
        return $this->start_away;
    }
	
    public function setStopAway($stop_away)
    {
		$this->stop_away = $stop_away;
    }

    public function getStopAway()
    {
        return $this->stop_away;
    }	
	
    public function setUserId($userid)
    {
		$this->userid = $userid;
    }

    public function getUserId()
    {
        return $this->userid;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

	public function getWorkHours()
    {
        return $this->workhours;
    }
    public function setWorkHours($workhours)
    {
        $this->workhours = $workhours;
    }
	
	public function getAway()
    {
        return $this->away;
    }
    public function setAway($away)
    {
        $this->away = $away;
    }
	
	public function getDate()
    {
        return $this->date;
    }
    public function setDate($date)
    {
        $this->date = $date;
    }
}