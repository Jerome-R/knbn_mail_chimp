<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Campaign
 *
 * @ORM\Table(name="app_campaign")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CampaignRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Campaign
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="id_campaign_name", type="string", length=255, unique=true, nullable=true)
     */
    private $idCampaignName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="canal_one", type="string", length=100, nullable=true)
     */
    private $canalOne = null; 

    /**
     * @var string
     *
     * @ORM\Column(name="canal_two", type="string", length=100, nullable=true)
     */
    private $canalTwo = null; 

    /**
     * @var string
     *
     * @ORM\Column(name="canal_three", type="string", length=100, nullable=true)
     */
    private $canalThree = null; 

    /**
     * @var string
     *
     * @ORM\Column(name="canal_four", type="string", length=100, nullable=true)
     */
    private $canalFour = null;  

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=100)
     */
    private $state;

    /**
     * @ORM\Column(name="nb_clients", type="integer")
     */
    private $nbClients = 0;

    /**
     * @ORM\Column(name="nb_contacted", type="integer")
     */
    private $nbContacted = 0;

    /**
     * @ORM\Column(name="nb_opt_out", type="integer")
     */
    private $nbOptOut = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", nullable=true)
     */
    private $visible;

    /**
     * @var string
     *
     * @ORM\Column(name="visible_by", type="string", length=100, nullable=true)
     */
    private $visibleBy = "all"; 

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_campaign", type="boolean", nullable=true)
     */
    private $activeCampaign;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_kpi", type="boolean", nullable=true)
     */
    private $activeKpi;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Recipient", mappedBy="campaign", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    protected $recipients;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    protected $image;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Tracking", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    protected $tracking;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Link", mappedBy="campaign", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    protected $links;

    public function __construct()
    {   
        $this->createdAt            = new \DateTime();
        $this->startDate            = new \DateTime();
        $this->endDate              = new \DateTime();
        $this->recipients           = new ArrayCollection();
        $this->links                = new ArrayCollection();
        $this->activeCampaign       = false;
        $this->activeKpi            = false;
        $this->visible              = true;
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
     * Set idCampaignName
     *
     * @param string $idCampaignName
     *
     * @return Campaign
     */
    public function setIdCampaignName($idCampaignName)
    {
        $this->idCampaignName = $idCampaignName;

        return $this;
    }

    /**
     * Get idCampaignName
     *
     * @return string
     */
    public function getIdCampaignName()
    {
        return $this->idCampaignName;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Campaign
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return Campaign
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Campaign
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Campaign
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set canalOne
     *
     * @param string $canalOne
     *
     * @return Campaign
     */
    public function setCanalOne($canalOne)
    {
        $this->canalOne = $canalOne;

        return $this;
    }

    /**
     * Get canalOne
     *
     * @return string
     */
    public function getCanalOne()
    {
        return $this->canalOne;
    }

    /**
     * Set canalTwo
     *
     * @param string $canalTwo
     *
     * @return Campaign
     */
    public function SetCanalTwo($canalTwo)
    {
        $this->canalTwo = $canalTwo;

        return $this;
    }

    /**
     * Get canalTwo
     *
     * @return string
     */
    public function getCanalTwo()
    {
        return $this->canalTwo;
    }

    /**
     * Set canalThree
     *
     * @param string $canalThree
     *
     * @return Campaign
     */
    public function setCanalThree($canalThree)
    {
        $this->canalThree = $canalThree;

        return $this;
    }

    /**
     * Get canalThree
     *
     * @return string
     */
    public function getCanalThree()
    {
        return $this->canalThree;
    }

    /**
     * Set canalFour
     *
     * @param string $canalFour
     *
     * @return Campaign
     */
    public function setCanalFour($canalFour)
    {
        $this->canalFour = $canalFour;

        return $this;
    }

    /**
     * Get canalFour
     *
     * @return string
     */
    public function getCanalFour()
    {
        return $this->canalFour;
    }


    public function addRecipient(Recipient $recipient)
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    public function removeRecipient(Recipient $recipient)
    {
        $this->recipients->removeElement($recipient);
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function addLink(Link $link)
    {
        $this->links[] = $link;
        return $this;
    }

    public function removeLink(Link $link)
    {
        $this->links->removeElement($link);
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setImage(Image $image = null)
    {
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setTracking(Tracking $tracking = null)
    {
        $this->tracking = $tracking;
        return $this;
    }

    public function getTracking()
    {
        return $this->tracking;
    }

    public function getClients()
    {
        return array_map(
            function ($recipient) {
                return $recipient->getClient();
            },
            $this->recipients->toArray()
        );
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Campaign
     */
    public function setStartDate($startDate)
    {
        if( !($startDate instanceof \DateTime) ) $startDate = new \DateTime($startDate);
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Campaign
     */
    public function setEndDate($endDate)
    {
        if( !($endDate instanceof \DateTime) ) $endDate = new \DateTime($endDate);
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Campaign
     */
    public function setState()
    {
        $date = new \DateTime();
        $date->setTime(00, 00, 00);

        if ( $date < $this->startDate ) {
            $this->state = "to be launched";
        }
        elseif ( $date <= $this->endDate ) {
            $this->state = "on going";
        }
        else{
            $this->state = "finished";
        }

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set activeCampaign
     *
     * @param boolean $activeCampaign
     *
     * @return Campaign
     */
    public function setActiveCampaign($activeCampaign)
    {
        $this->activeCampaign = $activeCampaign;
        
        return $this;
    }

    /**
     * Get activeCampaign
     *
     * @return string
     */
    public function getActiveCampaign()
    {
        return $this->activeCampaign;
    }

    /**
     * Set activeKpi
     *
     * @param boolean $activeKpi
     *
     * @return Campaign
     */
    public function setActiveKpi($activeKpi)
    {
        $this->activeKpi = $activeKpi;
        
        return $this;
    }

    /**
     * Get activeKpi
     *
     * @return string
     */
    public function getActiveKpi()
    {
        return $this->activeKpi;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Campaign
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        
        return $this;
    }

    /**
     * Get visible
     *
     * @return string
     */
    public function getVisible()
    {
        return $this->visible;
    }
    

    /**
     * Get visibleBy
     *
     * @return boolean
     */
    public function getVisibleBy()
    {
        return $this->visibleBy;
    }

    /**
     * Set visibleBy
     *
     * @param string $visibleBy
     *
     * @return Campaign
     */
    public function setVisibleBy($visibleBy)
    {
        $this->visibleBy = $visibleBy;
        
        return $this;
    }

    /**
     * Get nbClients
     *
     * @return integer
     */
    public function getNbClients()
    {
        return $this->nbClients;
    }

    /**
     * Set nbClients
     *
     * @param boolean $nbClients
     *
     * @return Campaign
     */
    public function setNbClients($nbClients)
    {
        $this->nbClients = $nbClients;
        
        return $this;
    }

    /**
     * Get nbContacted
     *
     * @return integer
     */
    public function getNbContacted()
    {
        return $this->nbContacted;
    }

    /**
     * Set nbContacted
     *
     * @param boolean $nbContacted
     *
     * @return Campaign
     */
    public function setNbContacted($nbContacted)
    {
        $this->nbContacted = $nbContacted;
        
        return $this;
    }

    public function increaseClient()
    {
        $this->nbClients++;
    }

    public function decreaseClient()
    {
        $this->nbClients--;
    }

    

    /**
     * Get nbOptOut
     *
     * @return integer
     */
    public function getNbOptOut()
    {
        return $this->nbOptOut;
    }

    /**
     * Set nbOptOut
     *
     * @param boolean $nbOptOut
     *
     * @return Campaign
     */
    public function setNbOptOut($nbOptOut)
    {
        $this->nbOptOut = $nbOptOut;
        
        return $this;
    }

    public function increaseOptOut()
    {
        $this->nbOptOut++;
    }

    public function decreaseOptOut()
    {
        $this->nbOptOut--;
    }

    // Function for sonata to render text-link relative to the entity

    /**
     * __toString
     * 
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateState() {
        $this->setState();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function validationCanals() {
        $canalOne     = $this->getCanalOne();
        $canalTwo     = $this->getCanalTwo();
        $canalThree   = $this->getCanalThree();
        $canalFour    = $this->getCanalFour();

        if( $canalFour == $canalThree || $canalFour == $canalTwo || $canalFour == $canalOne) {
            $this->setCanalFour(null);
        }

        if( $canalThree == $canalTwo || $canalThree == $canalOne) {
            $this->setCanalThree(null);
        }

        if( $canalTwo == $canalOne ) {
            $this->setCanalTwo(null);
        }
    }
}