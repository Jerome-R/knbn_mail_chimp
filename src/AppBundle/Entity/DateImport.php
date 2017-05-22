<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Image
 *
 * @ORM\Table(name="app_date_import")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class DateImport
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
     * @ORM\Column(name="date_import", type="date")
     */
    private $dateImport;
    
    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=255)
     */
    private $module;

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
     * Set module
     *
     * @param string $module
     * @return DateImport
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set dateImport
     *
     * @param \DateTime $dateImport
     *
     * @return Campaign
     */
    public function setDateImport($dateImport)
    {
        if( !($dateImport instanceof \DateTime) ) $dateImport = new \DateTime($dateImport);
        $this->dateImport = $dateImport;

        return $this;
    }

    /**
     * Get dateImport
     *
     * @return \DateTime
     */
    public function getDateImport()
    {
        return $this->dateImport;
    }

    /**
     * __toString
     * 
     * @return string
     */
    public function __toString() {
        return $this->getFullName();
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        
    }

    public function getFullName()
    {
        return "Import du moule ".$this->module;
    }
}
